# -*- mode: python ; coding: utf-8 -*-
"""PyInstaller: ejecutable unico y standalone (incluye core/ y assets/)."""
from pathlib import Path

PACKAGING = Path(SPECPATH).resolve()
APP = PACKAGING.parent                 # desktop/
REPO = APP.parent                      # raiz del repo
CORE = REPO / "core"
ASSETS = APP / "assets"
ENTRY = APP / "src" / "main.py"

a = Analysis(
    [str(ENTRY)],
    pathex=[str(APP / "src")],
    binaries=[],
    datas=[(str(CORE), "core"), (str(ASSETS), "assets")],
    hiddenimports=[],
    hookspath=[],
    runtime_hooks=[],
    excludes=["unittest", "doctest", "pdb", "pydoc", "difflib"],
    noarchive=False,
)

pyz = PYZ(a.pure)

exe = EXE(
    pyz,
    a.scripts,
    a.binaries,
    a.datas,
    [],
    name="EquipateFull",
    debug=False,
    bootloader_ignore_signals=False,
    strip=False,
    upx=False,
    runtime_tmpdir=None,
    console=False,
    disable_windowed_traceback=False,
    icon=str(ASSETS / "icon.ico"),
    version=str(PACKAGING / "version_info.txt"),
)
