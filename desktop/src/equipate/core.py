"""Carga y localiza los archivos del nucleo compartido (`core/`)."""
from __future__ import annotations

import json
import os
import sys
from pathlib import Path


def core_dir() -> Path:
    """Localiza la carpeta core/ (soporta ejecucion normal y PyInstaller)."""
    env = os.environ.get("EQUIPATE_CORE")
    if env and Path(env).is_dir():
        return Path(env)
    bundle = getattr(sys, "_MEIPASS", None)
    if bundle and (Path(bundle) / "core" / "schema.json").is_file():
        return Path(bundle) / "core"
    here = Path(__file__).resolve()
    for parent in here.parents:
        candidate = parent / "core"
        if (candidate / "schema.json").is_file():
            return candidate
    raise FileNotFoundError("No se encontro la carpeta core/.")


def _load(name: str) -> dict:
    with open(core_dir() / name, "r", encoding="utf-8") as fh:
        return json.load(fh)


def load_all() -> dict:
    return {
        "schema": _load("schema.json"),
        "rules": _load("rules.json"),
        "tokens": _load("tokens.json"),
        "seed": _load("seed.json"),
        "i18n": {
            "es": _load("i18n/es.json"),
            "en": _load("i18n/en.json"),
        },
        "cases": _load("cases/validation.json"),
    }
