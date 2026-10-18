"""Tema ttk construido desde core/tokens.json (claro/oscuro en runtime)."""
from __future__ import annotations

import tkinter as tk
from tkinter import ttk


def apply_theme(root: tk.Tk, tokens: dict, name: str) -> dict:
    """Aplica la paleta y devuelve el diccionario de colores activo."""
    c = tokens["themes"][name]
    font = (tokens["typography"]["fontFamily"].split(",")[0].strip(), 11)
    font_bold = (tokens["typography"]["fontFamily"].split(",")[0].strip(), 13, "bold")

    root.configure(bg=c["bg"])
    style = ttk.Style(root)
    try:
        style.theme_use("clam")
    except tk.TclError:
        pass

    style.configure(".", background=c["surface"], foreground=c["text"], font=font)
    style.configure("TFrame", background=c["surface"])
    style.configure("TLabel", background=c["surface"], foreground=c["text"])
    style.configure("Title.TLabel", font=font_bold, foreground=c["accent"], background=c["surface"])

    style.configure("TButton", background=c["surfaceAlt"], foreground=c["text"], padding=6, borderwidth=0)
    style.map("TButton", background=[("active", c["primary"])], foreground=[("active", c["textStrong"])])
    style.configure("Primary.TButton", background=c["primary"], foreground=c["textStrong"])
    style.map("Primary.TButton", background=[("active", c["accent"])])
    style.configure("Danger.TButton", background=c["danger"], foreground=c["textStrong"])

    style.configure("TEntry", fieldbackground=c["surfaceAlt"], foreground=c["text"], insertcolor=c["text"])
    style.configure("TCombobox", fieldbackground=c["surfaceAlt"], foreground=c["text"])
    style.configure("TSpinbox", fieldbackground=c["surfaceAlt"], foreground=c["text"])

    style.configure("TNotebook", background=c["bg"], borderwidth=0)
    style.configure("TNotebook.Tab", background=c["surfaceAlt"], foreground=c["text"], padding=(14, 6))
    style.map("TNotebook.Tab", background=[("selected", c["surface"])], foreground=[("selected", c["accent"])])

    style.configure(
        "Treeview",
        background=c["surface"],
        fieldbackground=c["surface"],
        foreground=c["text"],
        rowheight=26,
        borderwidth=0,
    )
    style.configure("Treeview.Heading", background=c["surfaceAlt"], foreground=c["accent"], font=font_bold)
    style.map("Treeview", background=[("selected", c["primary"])], foreground=[("selected", c["textStrong"])])

    root.option_add("*Toplevel.background", c["bg"])
    return c
