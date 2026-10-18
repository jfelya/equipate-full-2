"""Interfaz Tkinter/ttk de Equipate Full (escritorio)."""
from __future__ import annotations

import math
import tkinter as tk
from tkinter import messagebox, ttk

from .i18n import I18n
from .theme import apply_theme
from .validate import format_error, validate_cart, validate_entity


class LangToggle(ttk.Frame):
    """Un solo control para los dos idiomas: ES | EN (el activo resaltado)."""

    def __init__(self, parent, lang, command):
        super().__init__(parent)
        self.command = command
        self.labels = {}
        for index, code in enumerate(("es", "en")):
            if index:
                ttk.Label(self, text="/", style="LangOff.TLabel").pack(side="left")
            label = ttk.Label(self, text=code.upper(), padding=(4, 2), cursor="hand2")
            label.pack(side="left")
            label.bind("<Button-1>", lambda _e: self.command())
            self.labels[code] = label
        self.refresh(lang)

    def refresh(self, lang: str) -> None:
        for code, label in self.labels.items():
            label.configure(style="LangOn.TLabel" if code == lang else "LangOff.TLabel")


class ThemeIconButton(tk.Canvas):
    """Un solo boton de tema con icono vectorial (sol/luna)."""

    SIZE = 30

    def __init__(self, parent, theme_name, command, colors):
        super().__init__(parent, width=self.SIZE, height=self.SIZE, highlightthickness=0, bd=0)
        self.command = command
        self.theme_name = theme_name
        self.colors = colors
        self.configure(bg=colors["surface"], cursor="hand2")
        self.bind("<Button-1>", lambda _e: self.command())
        self._draw()

    def set_theme(self, theme_name: str, colors: dict) -> None:
        self.theme_name = theme_name
        self.colors = colors
        self.configure(bg=colors["surface"])
        self._draw()

    def _draw(self) -> None:
        self.delete("all")
        color = self.colors["accent"]
        center = self.SIZE / 2
        if self.theme_name == "dark":
            # Muestra un sol: al pulsar se pasa a claro.
            self.create_oval(center - 5, center - 5, center + 5, center + 5, outline=color, width=2)
            for i in range(8):
                angle = i * math.pi / 4
                x1, y1 = center + math.cos(angle) * 8, center + math.sin(angle) * 8
                x2, y2 = center + math.cos(angle) * 11, center + math.sin(angle) * 11
                self.create_line(x1, y1, x2, y2, fill=color, width=2)
        else:
            # Muestra una luna: al pulsar se pasa a oscuro.
            self.create_oval(center - 8, center - 8, center + 8, center + 8, outline=color, width=2)
            self.create_oval(center - 2, center - 10, center + 14, center + 6, fill=self.colors["surface"], outline="")


class App:
    def __init__(self, root: tk.Tk, core: dict, store, start_lang: str = "es", start_theme: str = "dark"):
        self.root = root
        self.core = core
        self.store = store
        self.rules = core["rules"]
        self.i18n = I18n(core["i18n"], start_lang)
        self.theme_name = start_theme
        self.user = None
        self.cart: list[dict] = []

        self.root.title(self.i18n.t("app.name"))
        self.root.geometry("1000x680")
        self.root.minsize(860, 560)
        self.colors = apply_theme(self.root, core["tokens"], self.theme_name)

        self._build_header()
        self._build_notebook()
        self._build_login()
        self._build_catalog()
        self._build_cart()
        self._build_invoices()
        self._build_admin()
        self._apply_session_state()

    # ------------------------------------------------------------------ util
    def t(self, key: str, **p) -> str:
        return self.i18n.t(key, **p)

    def money(self, value) -> str:
        return f"{float(value):,.0f} {self.i18n.raw('currency.symbol')}"

    def _status(self, label: tk.Widget, text: str, kind: str = "info") -> None:
        colors = self.colors
        key = {"info": "info", "ok": "success", "error": "danger", "muted": "muted"}[kind]
        label.configure(text=text, foreground=colors[key])

    # ---------------------------------------------------------------- header
    def _build_header(self) -> None:
        bar = ttk.Frame(self.root, padding=(16, 10))
        bar.pack(fill="x")
        self.lbl_title = ttk.Label(bar, text=self.t("app.name"), style="Title.TLabel")
        self.lbl_title.pack(side="left")

        self.lbl_user = ttk.Label(bar, text="")
        self.lbl_user.pack(side="right", padx=8)
        self.btn_logout = ttk.Button(bar, text=self.t("nav.logout"), command=self.logout)
        self.btn_logout.pack(side="right")
        self.btn_lang = LangToggle(bar, self.i18n.lang, self.toggle_lang)
        self.btn_lang.pack(side="right", padx=6)
        self.btn_theme = ThemeIconButton(bar, self.theme_name, self.toggle_theme, self.colors)
        self.btn_theme.pack(side="right", padx=6)

    # -------------------------------------------------------------- notebook
    def _build_notebook(self) -> None:
        self.nb = ttk.Notebook(self.root)
        self.nb.pack(fill="both", expand=True, padx=12, pady=8)
        self.tab_login = ttk.Frame(self.nb, padding=16)
        self.tab_catalog = ttk.Frame(self.nb, padding=16)
        self.tab_cart = ttk.Frame(self.nb, padding=16)
        self.tab_invoices = ttk.Frame(self.nb, padding=16)
        self.tab_admin = ttk.Frame(self.nb, padding=16)
        for tab in (self.tab_login, self.tab_catalog, self.tab_cart, self.tab_invoices, self.tab_admin):
            self.nb.add(tab, text="")

    def _retitle_tabs(self) -> None:
        for idx, key in enumerate(
            ["nav.login", "nav.catalog", "nav.cart", "nav.invoices", "nav.admin"]
        ):
            self.nb.tab(idx, text=self.t(key))

    # ----------------------------------------------------------------- login
    def _build_login(self) -> None:
        f = self.tab_login
        ttk.Label(f, text=self.t("messages.welcome"), style="Title.TLabel").grid(row=0, column=0, columnspan=2, pady=(0, 16))
        self.lbl_login_user = ttk.Label(f, text=self.t("fields.usuario"))
        self.lbl_login_user.grid(row=1, column=0, sticky="e", padx=8, pady=6)
        self.var_login_user = tk.StringVar()
        ttk.Entry(f, textvariable=self.var_login_user, width=28).grid(row=1, column=1, pady=6)
        self.lbl_login_pass = ttk.Label(f, text=self.t("fields.contrasena"))
        self.lbl_login_pass.grid(row=2, column=0, sticky="e", padx=8, pady=6)
        self.var_login_pass = tk.StringVar()
        ttk.Entry(f, textvariable=self.var_login_pass, show="*", width=28).grid(row=2, column=1, pady=6)
        self.btn_login = ttk.Button(f, text=self.t("actions.ingresar"), style="Primary.TButton", command=self.do_login)
        self.btn_login.grid(row=3, column=0, columnspan=2, pady=14, ipadx=20)
        self.lbl_login_status = ttk.Label(f, text="")
        self.lbl_login_status.grid(row=4, column=0, columnspan=2)
        f.bind_all("<Return>", lambda e: self.do_login() if self.nb.index("current") == 0 else None)

    def do_login(self) -> None:
        values = {"usuario": self.var_login_user.get().strip().lower(), "contrasena": self.var_login_pass.get()}
        errors = validate_entity(self.rules, "usuario", {"usuario": values["usuario"], "contrasena": values["contrasena"]})
        errors = [e for e in errors if e["field"] in ("usuario", "contrasena")]
        if errors:
            self._status(self.lbl_login_status, self._err(errors[0]), "error")
            return
        user = self.store.authenticate(values["usuario"], values["contrasena"])
        if not user:
            self._status(self.lbl_login_status, self.t("errors.invalidCredentials"), "error")
            return
        self.user = user
        self.cart = []
        self.var_login_pass.set("")
        self._status(self.lbl_login_status, self.t("messages.session_user", usuario=user["usuario"]), "ok")
        self._apply_session_state()
        self.refresh_catalog()
        self.refresh_cart()
        self.refresh_invoices()
        self.nb.select(1)

    def logout(self) -> None:
        self.user = None
        self.cart = []
        self._apply_session_state()
        self.nb.select(0)
        self._status(self.lbl_login_status, self.t("messages.logout_ok"), "muted")

    def _apply_session_state(self) -> None:
        logged = self.user is not None
        is_admin = logged and self.user["nivel"] == "admin"
        self.lbl_user.configure(text=self.t("messages.session_user", usuario=self.user["usuario"]) if logged else "")
        for idx, enabled in enumerate([not logged, logged, logged, logged, is_admin]):
            self.nb.tab(idx, state="normal" if enabled else "disabled")
        self.btn_logout.configure(state="normal" if logged else "disabled")
        self._retitle_tabs()

    # --------------------------------------------------------------- catalog
    def _build_catalog(self) -> None:
        f = self.tab_catalog
        ttk.Label(f, text=self.t("table.productos"), style="Title.TLabel").pack(anchor="w")
        cols = ("nombre", "stock", "precio")
        self.tree_catalog = ttk.Treeview(f, columns=cols, show="headings", height=12)
        for col, key in zip(cols, ["fields.producto", "fields.stock", "fields.precio"]):
            self.tree_catalog.heading(col, text=self.t(key))
        self.tree_catalog.pack(fill="both", expand=True, pady=10)
        row = ttk.Frame(f)
        row.pack(fill="x")
        self.btn_add_cart = ttk.Button(row, text=self.t("actions.agregar"), style="Primary.TButton", command=self.add_to_cart)
        self.btn_add_cart.pack(side="left")
        self.btn_refresh = ttk.Button(row, text="Actualizar", command=self.refresh_catalog)
        self.btn_refresh.pack(side="left", padx=6)
        self.lbl_catalog_status = ttk.Label(f, text="")
        self.lbl_catalog_status.pack(anchor="w", pady=6)

    def refresh_catalog(self) -> None:
        self.tree_catalog.delete(*self.tree_catalog.get_children())
        for p in self.store.productos():
            self.tree_catalog.insert("", "end", iid=str(p["id"]),
                                     values=(p["nombre"], p["stock"], self.money(p["precio"])))

    def add_to_cart(self) -> None:
        sel = self.tree_catalog.selection()
        if not sel:
            self._status(self.lbl_catalog_status, self.t("messages.empty_cart"), "muted")
            return
        pid = int(sel[0])
        product = self.store._by_id("producto", pid)
        existing = next((i for i in self.cart if i["id_producto"] == pid), None)
        if existing:
            existing["cantidad"] += 1
        else:
            self.cart.append({"id_producto": pid, "nombre": product["nombre"], "precio": product["precio"], "cantidad": 1})
        errors = validate_cart(self.rules, self.cart, {p["id"]: p for p in self.store.productos()})
        if errors:
            existing["cantidad"] -= 1 if existing else 0
            if not existing:
                self.cart.pop()
            self._status(self.lbl_catalog_status, self._err(errors[0], field=errors[0].get("field")), "error")
            return
        self._status(self.lbl_catalog_status, self.t("messages.saved_ok"), "ok")
        self.refresh_cart()

    # ------------------------------------------------------------------ cart
    def _build_cart(self) -> None:
        f = self.tab_cart
        self.lbl_cart_title = ttk.Label(f, text="", style="Title.TLabel")
        self.lbl_cart_title.pack(anchor="w")
        cols = ("nombre", "precio", "cantidad")
        self.tree_cart = ttk.Treeview(f, columns=cols, show="headings", height=10)
        for col, key in zip(cols, ["fields.producto", "fields.precio", "fields.cantidad"]):
            self.tree_cart.heading(col, text=self.t(key))
        self.tree_cart.pack(fill="both", expand=True, pady=10)
        row = ttk.Frame(f)
        row.pack(fill="x")
        ttk.Button(row, text="−", command=lambda: self.change_qty(-1)).pack(side="left")
        ttk.Button(row, text="+", command=lambda: self.change_qty(1)).pack(side="left", padx=6)
        self.btn_clear = ttk.Button(row, text=self.t("actions.vaciar"), command=self.clear_cart)
        self.btn_clear.pack(side="left", padx=6)
        self.btn_buy = ttk.Button(row, text=self.t("actions.comprar"), style="Primary.TButton", command=self.checkout)
        self.btn_buy.pack(side="right")
        self.lbl_total = ttk.Label(f, text="", style="Title.TLabel")
        self.lbl_total.pack(anchor="e", pady=6)
        self.lbl_cart_status = ttk.Label(f, text="")
        self.lbl_cart_status.pack(anchor="w")

    def refresh_cart(self) -> None:
        self.tree_cart.delete(*self.tree_cart.get_children())
        for i, item in enumerate(self.cart):
            self.tree_cart.insert("", "end", iid=str(i),
                                  values=(item["nombre"], self.money(item["precio"]), item["cantidad"]))
        total = sum(i["precio"] * i["cantidad"] for i in self.cart)
        self.lbl_total.configure(text=f"{self.t('fields.total')}: {self.money(total)}")
        user = self.user["usuario"] if self.user else ""
        self.lbl_cart_title.configure(text=self.t("table.carrito", usuario=user))

    def change_qty(self, delta: int) -> None:
        sel = self.tree_cart.selection()
        if not sel:
            return
        idx = int(sel[0])
        self.cart[idx]["cantidad"] += delta
        if self.cart[idx]["cantidad"] < 1:
            self.cart.pop(idx)
        else:
            errors = validate_cart(self.rules, self.cart, {p["id"]: p for p in self.store.productos()})
            if errors:
                self.cart[idx]["cantidad"] -= delta
                self._status(self.lbl_cart_status, self._err(errors[0], field=errors[0].get("field")), "error")
                return
        self.refresh_cart()

    def clear_cart(self) -> None:
        self.cart = []
        self.refresh_cart()
        self._status(self.lbl_cart_status, "", "muted")

    def checkout(self) -> None:
        if not self.cart:
            self._status(self.lbl_cart_status, self.t("errors.emptyCart"), "error")
            return
        products = {p["id"]: p for p in self.store.productos()}
        errors = validate_cart(self.rules, self.cart, products)
        if errors:
            self._status(self.lbl_cart_status, self._err(errors[0], field=errors[0].get("field")), "error")
            return
        id_factura = self.store.checkout(self.user["id"], self.cart)
        self.cart = []
        self.refresh_cart()
        self.refresh_catalog()
        self.refresh_invoices()
        self._status(self.lbl_cart_status, self.t("messages.purchase_ok", id=id_factura), "ok")
        self.nb.select(3)

    # -------------------------------------------------------------- invoices
    def _build_invoices(self) -> None:
        f = self.tab_invoices
        self.lbl_inv_title = ttk.Label(f, text="", style="Title.TLabel")
        self.lbl_inv_title.pack(anchor="w")
        cols = ("id", "fecha", "total")
        self.tree_invoices = ttk.Treeview(f, columns=cols, show="headings", height=12)
        for col, key in zip(cols, ["fields.usuario", "fields.fecha", "fields.total"]):
            self.tree_invoices.heading(col, text=self.t(key))
        self.tree_invoices.heading("id", text="#")
        self.tree_invoices.pack(fill="both", expand=True, pady=10)
        ttk.Button(f, text=self.t("actions.ver_detalle"), command=self.show_invoice_detail).pack(anchor="w")
        self.lbl_inv_status = ttk.Label(f, text="")
        self.lbl_inv_status.pack(anchor="w", pady=6)

    def refresh_invoices(self) -> None:
        self.tree_invoices.delete(*self.tree_invoices.get_children())
        if not self.user:
            return
        invoices = self.store.facturas_by_cliente(self.user["id"])
        for inv in invoices:
            self.tree_invoices.insert("", "end", iid=str(inv["id"]),
                                      values=(inv["id"], inv["fecha"][:10], self.money(inv["preciototal"])))
        self.lbl_inv_title.configure(text=self.t("table.facturas", usuario=self.user["usuario"]))
        self._status(self.lbl_inv_status, "" if invoices else self.t("messages.empty_invoices"), "muted")

    def show_invoice_detail(self) -> None:
        sel = self.tree_invoices.selection()
        if not sel:
            return
        id_factura = int(sel[0])
        win = tk.Toplevel(self.root)
        win.title(self.t("table.detalle_factura", id=id_factura))
        win.geometry("560x420")
        win.configure(bg=self.colors["bg"])
        cols = ("producto", "cantidad", "precio")
        tree = ttk.Treeview(win, columns=cols, show="headings")
        for col, key in zip(cols, ["fields.producto", "fields.cantidad", "fields.precio"]):
            tree.heading(col, text=self.t(key))
        tree.pack(fill="both", expand=True, padx=12, pady=12)
        for d in self.store.detalle_by_factura(id_factura):
            tree.insert("", "end", values=(d["nombre_producto"], d["cantidad"], self.money(d["precio"])))

    # ----------------------------------------------------------------- admin
    def _build_admin(self) -> None:
        f = self.tab_admin
        sub = ttk.Notebook(f)
        sub.pack(fill="both", expand=True)
        prod = ttk.Frame(sub, padding=16)
        usr = ttk.Frame(sub, padding=16)
        sub.add(prod, text=self.t("admin.products"))
        sub.add(usr, text=self.t("admin.users"))

        # -- producto
        ttk.Label(prod, text=self.t("admin.new_product"), style="Title.TLabel").grid(row=0, column=0, columnspan=2, pady=(0, 12))
        self.p_vars = {}
        for r, key in enumerate(["nombre", "stock", "precio"], start=1):
            ttk.Label(prod, text=self.t(f"fields.{key}")).grid(row=r, column=0, sticky="e", padx=8, pady=4)
            self.p_vars[key] = tk.StringVar()
            ttk.Entry(prod, textvariable=self.p_vars[key], width=30).grid(row=r, column=1, pady=4)
        ttk.Button(prod, text=self.t("actions.registrar"), style="Primary.TButton", command=self.register_product).grid(row=4, column=0, columnspan=2, pady=12)
        self.lbl_prod_status = ttk.Label(prod, text="")
        self.lbl_prod_status.grid(row=5, column=0, columnspan=2)
        self.tree_admin_products = ttk.Treeview(prod, columns=("nombre", "stock", "precio"), show="headings", height=6)
        for col, key in zip(("nombre", "stock", "precio"), ["fields.producto", "fields.stock", "fields.precio"]):
            self.tree_admin_products.heading(col, text=self.t(key))
        self.tree_admin_products.grid(row=6, column=0, columnspan=2, pady=10, sticky="ew")
        ttk.Button(prod, text=self.t("actions.borrar"), style="Danger.TButton", command=self.delete_product).grid(row=7, column=0, columnspan=2)

        # -- usuario
        ttk.Label(usr, text=self.t("admin.new_user"), style="Title.TLabel").grid(row=0, column=0, columnspan=2, pady=(0, 12))
        self.u_vars = {}
        fields = ["nombre", "apellido", "usuario", "correo", "contrasena", "contrasena2", "respuesta_secreta"]
        for r, key in enumerate(fields, start=1):
            ttk.Label(usr, text=self.t(f"fields.{key}")).grid(row=r, column=0, sticky="e", padx=8, pady=3)
            self.u_vars[key] = tk.StringVar()
            show = "*" if key.startswith("contrasena") else ""
            ttk.Entry(usr, textvariable=self.u_vars[key], width=30, show=show).grid(row=r, column=1, pady=3)
        ttk.Label(usr, text=self.t("fields.nivel")).grid(row=8, column=0, sticky="e", padx=8, pady=3)
        self.u_vars["nivel"] = tk.StringVar(value="cliente")
        ttk.Combobox(usr, textvariable=self.u_vars["nivel"], values=["cliente", "admin"], state="readonly", width=27).grid(row=8, column=1, pady=3)
        ttk.Label(usr, text=self.t("fields.pregunta_secreta")).grid(row=9, column=0, sticky="e", padx=8, pady=3)
        self.u_vars["pregunta_secreta"] = tk.StringVar(value="Color Favorito")
        ttk.Combobox(usr, textvariable=self.u_vars["pregunta_secreta"],
                     values=self.rules["validation"]["usuario"]["pregunta_secreta"]["values"],
                     state="readonly", width=27).grid(row=9, column=1, pady=3)
        ttk.Button(usr, text=self.t("actions.registrar"), style="Primary.TButton", command=self.register_user).grid(row=10, column=0, columnspan=2, pady=12)
        self.lbl_usr_status = ttk.Label(usr, text="")
        self.lbl_usr_status.grid(row=11, column=0, columnspan=2)

    def register_product(self) -> None:
        values = {k: self.p_vars[k].get().strip() for k in ("nombre", "stock", "precio")}
        errors = validate_entity(self.rules, "producto", values)
        if errors:
            self._status(self.lbl_prod_status, self._err(errors[0]), "error")
            return
        if self.store.producto_by_nombre(values["nombre"]):
            self._status(self.lbl_prod_status, self.t("errors.productExists", value=values["nombre"]), "error")
            return
        self.store.insert_producto({"nombre": values["nombre"], "stock": int(values["stock"]), "precio": float(values["precio"])})
        for v in self.p_vars.values():
            v.set("")
        self._status(self.lbl_prod_status, self.t("messages.saved_ok"), "ok")
        self.refresh_catalog()
        self.refresh_admin_products()

    def delete_product(self) -> None:
        sel = self.tree_admin_products.selection()
        if not sel:
            return
        self.store.delete_producto(int(sel[0]))
        self.refresh_catalog()
        self.refresh_admin_products()

    def refresh_admin_products(self) -> None:
        self.tree_admin_products.delete(*self.tree_admin_products.get_children())
        for p in self.store.productos():
            self.tree_admin_products.insert("", "end", iid=str(p["id"]),
                                            values=(p["nombre"], p["stock"], self.money(p["precio"])))

    def register_user(self) -> None:
        values = {k: v.get().strip() for k, v in self.u_vars.items()}
        values["usuario"] = values["usuario"].lower()
        values["respuesta_secreta"] = values["respuesta_secreta"].lower()
        errors = validate_entity(self.rules, "usuario", values)
        if errors:
            self._status(self.lbl_usr_status, self._err(errors[0]), "error")
            return
        if self.store.usuario_by_usuario(values["usuario"]):
            self._status(self.lbl_usr_status, self.t("errors.userExists", value=values["usuario"]), "error")
            return
        self.store.insert_usuario(values)
        for v in self.u_vars.values():
            v.set("")
        self._status(self.lbl_usr_status, self.t("messages.saved_ok"), "ok")

    # ---------------------------------------------------------------- errors
    def _err(self, err: dict, field: str | None = None) -> str:
        label = self.t(f"fields.{field or err.get('field')}")
        if label == f"fields.{field or err.get('field')}":
            label = field or err.get("field") or ""
        return format_error(self.i18n.dictionaries[self.i18n.lang], err, label)

    # --------------------------------------------------------------- theming
    def toggle_theme(self) -> None:
        self.theme_name = "light" if self.theme_name == "dark" else "dark"
        self.colors = apply_theme(self.root, self.core["tokens"], self.theme_name)
        self.btn_theme.set_theme(self.theme_name, self.colors)

    def toggle_lang(self) -> None:
        self.i18n.toggle()
        self.btn_lang.refresh(self.i18n.lang)
        self.retranslate()

    def retranslate(self) -> None:
        self.root.title(self.t("app.name"))
        self.lbl_title.configure(text=self.t("app.name"))
        self.btn_logout.configure(text=self.t("nav.logout"))
        self.btn_lang.refresh(self.i18n.lang)
        self.lbl_login_user.configure(text=self.t("fields.usuario"))
        self.lbl_login_pass.configure(text=self.t("fields.contrasena"))
        self.btn_login.configure(text=self.t("actions.ingresar"))
        self.btn_add_cart.configure(text=self.t("actions.agregar"))
        self.btn_clear.configure(text=self.t("actions.vaciar"))
        self.btn_buy.configure(text=self.t("actions.comprar"))
        for col, key in zip(("nombre", "stock", "precio"), ["fields.producto", "fields.stock", "fields.precio"]):
            self.tree_catalog.heading(col, text=self.t(key))
            self.tree_admin_products.heading(col, text=self.t(key))
        for col, key in zip(("nombre", "precio", "cantidad"), ["fields.producto", "fields.precio", "fields.cantidad"]):
            self.tree_cart.heading(col, text=self.t(key))
        for col, key in zip(("id", "fecha", "total"), ["fields.usuario", "fields.fecha", "fields.total"]):
            self.tree_invoices.heading(col, text=self.t(key))
        self.tree_invoices.heading("id", text="#")
        self._apply_session_state()
        if self.user:
            self.refresh_cart()
            self.refresh_invoices()
