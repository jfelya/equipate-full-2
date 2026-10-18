"""Self-test de paridad: valida que el motor de reglas cumple core/cases."""
from __future__ import annotations

import sys
import tempfile
from pathlib import Path

sys.path.insert(0, str(Path(__file__).resolve().parent))

from equipate import Store, load_all  # noqa: E402
from equipate.validate import validate_entity  # noqa: E402

passed = 0
failed = 0


def check(name: str, got, expected) -> None:
    global passed, failed
    if got == expected:
        passed += 1
        print(f"  PASS  {name}")
    else:
        failed += 1
        print(f"  FAIL  {name}\n        esperado={expected}\n        obtenido={got}")


def main() -> int:
    core = load_all()
    rules = core["rules"]
    cases = core["cases"]

    print("Validacion por campo:")
    for c in cases["cases"]:
        errors = validate_entity(rules, c["entity"], {c["field"]: c["input"]})
        keys = [e["key"] for e in errors if e["field"] == c["field"]]
        check(c["name"], keys, c["expect"])

    print("Validacion de formulario:")
    for c in cases["formCases"]:
        errors = validate_entity(rules, c["entity"], c["values"])
        fields = sorted({e["field"] for e in errors})
        keys = sorted({e["key"] for e in errors})
        check(c["name"] + " (campos)", fields, sorted(c["expectFields"]))
        check(c["name"] + " (claves)", keys, sorted(c["expectKeys"]))

    print("Checkout:")
    for c in cases["checkoutCases"]:
        with tempfile.TemporaryDirectory() as tmp:
            store = Store(str(Path(tmp) / "t.db"), core["schema"])
            uid = store.insert_usuario({"nombre": "T", "apellido": "T", "usuario": "t", "correo": "t@t.com",
                                        "contrasena": "xxxx", "nivel": "cliente",
                                        "pregunta_secreta": "Color Favorito", "respuesta_secreta": "aa"})
            pid = store.insert_producto(c["product"])
            cart = [{"id_producto": pid, "nombre": c["product"]["nombre"],
                     "precio": c["product"]["precio"], "cantidad": item["cantidad"]} for item in c["cart"]]
            id_factura = store.checkout(uid, cart)
            factura = store._by_id("factura", id_factura)
            stock_after = store._by_id("producto", pid)["stock"]
            check(c["name"] + " (total)", factura["preciototal"], c["expectTotal"])
            check(c["name"] + " (stock)", stock_after, c["expectStockAfter"])
            store.close()

    print(f"\n{passed} passed, {failed} failed")
    return 1 if failed else 0


if __name__ == "__main__":
    raise SystemExit(main())
