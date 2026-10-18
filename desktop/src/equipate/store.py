"""Persistencia en SQLite. Espejo de apps/web/src/store.js (IndexedDB)."""
from __future__ import annotations

import hashlib
import sqlite3
from datetime import datetime, timezone

TYPE_MAP = {"integer": "INTEGER", "string": "TEXT", "number": "REAL", "enum": "TEXT", "datetime": "TEXT"}


def sha256(text: str) -> str:
    return hashlib.sha256(text.encode("utf-8")).hexdigest()


def now_iso() -> str:
    return datetime.now(timezone.utc).isoformat(timespec="seconds")


class Store:
    def __init__(self, db_path: str, schema: dict, seed: dict | None = None):
        self.schema = schema
        self.conn = sqlite3.connect(db_path)
        self.conn.row_factory = sqlite3.Row
        self._migrate()
        if seed:
            self.seed_if_empty(seed)

    # --- esquema ---------------------------------------------------------
    def _migrate(self) -> None:
        for entity, meta in self.schema["entities"].items():
            cols = []
            for field, spec in meta["fields"].items():
                col = f'"{field}" {TYPE_MAP.get(spec["type"], "TEXT")}'
                if field == meta["primaryKey"]:
                    col += " PRIMARY KEY"
                    if meta.get("autoIncrement"):
                        col = col.replace("INTEGER PRIMARY KEY", "INTEGER PRIMARY KEY AUTOINCREMENT")
                if spec.get("unique"):
                    col += " UNIQUE"
                cols.append(col)
            self.conn.execute(f'CREATE TABLE IF NOT EXISTS "{meta["table"]}" ({", ".join(cols)})')
        self.conn.commit()

    def _table(self, entity: str) -> str:
        return self.schema["entities"][entity]["table"]

    # --- seed ------------------------------------------------------------
    def seed_if_empty(self, seed: dict) -> None:
        if self.conn.execute(f'SELECT COUNT(*) FROM "{self._table("usuario")}"').fetchone()[0] == 0:
            for u in seed["usuarios"]:
                self.insert_usuario(u)
        if self.conn.execute(f'SELECT COUNT(*) FROM "{self._table("producto")}"').fetchone()[0] == 0:
            for p in seed["productos"]:
                self.insert_producto(p)

    # --- genericos -------------------------------------------------------
    def _all(self, entity: str) -> list[dict]:
        rows = self.conn.execute(f'SELECT * FROM "{self._table(entity)}"').fetchall()
        return [dict(r) for r in rows]

    def _by_id(self, entity: str, value) -> dict | None:
        meta = self.schema["entities"][entity]
        row = self.conn.execute(
            f'SELECT * FROM "{meta["table"]}" WHERE "{meta["primaryKey"]}" = ?', (value,)
        ).fetchone()
        return dict(row) if row else None

    def _insert(self, entity: str, data: dict) -> int:
        meta = self.schema["entities"][entity]
        pk = meta["primaryKey"]
        cols = [f for f in data if f in meta["fields"] and f != pk]
        placeholders = ", ".join("?" for _ in cols)
        names = ", ".join(f'"{c}"' for c in cols)
        cur = self.conn.execute(
            f'INSERT INTO "{meta["table"]}" ({names}) VALUES ({placeholders})', [data[c] for c in cols]
        )
        self.conn.commit()
        return cur.lastrowid

    def _update(self, entity: str, pk_value, data: dict) -> None:
        meta = self.schema["entities"][entity]
        pk = meta["primaryKey"]
        cols = [f for f in data if f in meta["fields"] and f != pk]
        assignments = ", ".join(f'"{c}" = ?' for c in cols)
        self.conn.execute(
            f'UPDATE "{meta["table"]}" SET {assignments} WHERE "{pk}" = ?',
            [data[c] for c in cols] + [pk_value],
        )
        self.conn.commit()

    def _delete(self, entity: str, pk_value) -> None:
        meta = self.schema["entities"][entity]
        self.conn.execute(f'DELETE FROM "{meta["table"]}" WHERE "{meta["primaryKey"]}" = ?', (pk_value,))
        self.conn.commit()

    # --- usuarios --------------------------------------------------------
    def insert_usuario(self, data: dict) -> int:
        payload = dict(data)
        payload.setdefault("contrasena_hash", sha256(payload.get("contrasena", "")))
        payload.pop("contrasena", None)
        payload.pop("contrasena2", None)
        payload.setdefault("creado", now_iso())
        return self._insert("usuario", payload)

    def update_usuario(self, uid, data: dict) -> None:
        payload = dict(data)
        if payload.get("contrasena"):
            payload["contrasena_hash"] = sha256(payload["contrasena"])
        payload.pop("contrasena", None)
        payload.pop("contrasena2", None)
        # Si no se cambia la contrasena, se conserva el hash existente.
        if "contrasena_hash" not in data:
            payload.pop("contrasena_hash", None)
        self._update("usuario", uid, payload)

    def usuarios(self) -> list[dict]:
        return self._all("usuario")

    def usuario_by_usuario(self, usuario: str) -> dict | None:
        row = self.conn.execute(
            f'SELECT * FROM "{self._table("usuario")}" WHERE "usuario" = ?', (usuario,)
        ).fetchone()
        return dict(row) if row else None

    # --- productos -------------------------------------------------------
    def insert_producto(self, data: dict) -> int:
        payload = dict(data)
        payload.setdefault("creado", now_iso())
        return self._insert("producto", payload)

    def update_producto(self, pid, data: dict) -> None:
        self._update("producto", pid, data)

    def delete_producto(self, pid) -> None:
        self._delete("producto", pid)

    def productos(self) -> list[dict]:
        return self._all("producto")

    def producto_by_nombre(self, nombre: str) -> dict | None:
        row = self.conn.execute(
            f'SELECT * FROM "{self._table("producto")}" WHERE "nombre" = ?', (nombre,)
        ).fetchone()
        return dict(row) if row else None

    # --- autenticacion ---------------------------------------------------
    def authenticate(self, usuario: str, contrasena: str) -> dict | None:
        user = self.usuario_by_usuario(usuario.lower().strip())
        if user and user["contrasena_hash"] == sha256(contrasena):
            return user
        return None

    # --- facturacion -----------------------------------------------------
    def facturas_by_cliente(self, id_cliente: int) -> list[dict]:
        rows = self.conn.execute(
            f'SELECT * FROM "{self._table("factura")}" WHERE "id_cliente" = ? ORDER BY "id" DESC',
            (id_cliente,),
        ).fetchall()
        return [dict(r) for r in rows]

    def detalle_by_factura(self, id_factura: int) -> list[dict]:
        rows = self.conn.execute(
            f'SELECT * FROM "{self._table("detalle")}" WHERE "id_factura" = ?', (id_factura,)
        ).fetchall()
        return [dict(r) for r in rows]

    def checkout(self, id_cliente: int, cart: list[dict]) -> int:
        """Crea factura + detalle y descuenta stock en una sola transaccion."""
        total = sum(item["precio"] * item["cantidad"] for item in cart)
        cur = self.conn.execute(
            f'INSERT INTO "{self._table("factura")}" ("id_cliente", "fecha", "preciototal") VALUES (?, ?, ?)',
            (id_cliente, now_iso(), total),
        )
        id_factura = cur.lastrowid
        for item in cart:
            self.conn.execute(
                f'INSERT INTO "{self._table("detalle")}" '
                '("id_factura", "id_producto", "nombre_producto", "cantidad", "precio") VALUES (?, ?, ?, ?, ?)',
                (id_factura, item["id_producto"], item["nombre"], item["cantidad"], item["precio"]),
            )
            self.conn.execute(
                f'UPDATE "{self._table("producto")}" SET "stock" = "stock" - ? WHERE "id" = ?',
                (item["cantidad"], item["id_producto"]),
            )
        self.conn.commit()
        return id_factura

    def close(self) -> None:
        self.conn.close()
