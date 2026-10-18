"""Paquete de la app de escritorio Equipate Full."""
from .app import App
from .core import load_all
from .i18n import I18n
from .store import Store

__all__ = ["App", "Store", "I18n", "load_all"]
