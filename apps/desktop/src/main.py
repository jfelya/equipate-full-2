"""Punto de entrada de la app de escritorio."""
from __future__ import annotations

import ctypes
import sys
import tkinter as tk
from pathlib import Path

# Permite ejecutar tanto `py src/main.py` como el paquete instalado.
sys.path.insert(0, str(Path(__file__).resolve().parent))

from equipate import App, Store, load_all  # noqa: E402


def enable_dpi_awareness() -> None:
    try:
        ctypes.windll.shcore.SetProcessDpiAwareness(1)  # type: ignore[attr-defined]
    except Exception:
        try:
            ctypes.windll.user32.SetProcessDPIAware()  # type: ignore[attr-defined]
        except Exception:
            pass


def db_path() -> str:
    # Fuera del directorio de trabajo si el ejecutable está empaquetado.
    if getattr(sys, "frozen", False):
        base = Path.home() / ".equipate_full"
        base.mkdir(parents=True, exist_ok=True)
        return str(base / "equipate.db")
    return str(Path(__file__).resolve().parents[1] / "equipate.db")


def icon_path() -> str | None:
    candidates = []
    bundle = getattr(sys, "_MEIPASS", None)
    if bundle:
        candidates.append(Path(bundle) / "assets" / "icon.ico")
    candidates.append(Path(__file__).resolve().parents[1] / "assets" / "icon.ico")
    for candidate in candidates:
        if candidate.is_file():
            return str(candidate)
    return None


def main() -> None:
    enable_dpi_awareness()
    core = load_all()
    store = Store(db_path(), core["schema"], core["seed"])
    root = tk.Tk()
    icon = icon_path()
    if icon:
        try:
            root.iconbitmap(default=icon)
        except tk.TclError:
            pass
    App(root, core, store)
    root.mainloop()
    store.close()


if __name__ == "__main__":
    main()
