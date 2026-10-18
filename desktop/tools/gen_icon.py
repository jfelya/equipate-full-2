"""Genera apps/desktop/assets/icon.ico (y icon.png) sin dependencias externas.

Dibuja una bolsa de compra sobre un cuadrado redondeado. Antialias por
supermuestreo. El ICO usa entradas PNG (soportado por Windows Vista+).
"""
from __future__ import annotations

import struct
import zlib
from pathlib import Path

ACCENT = (190, 144, 99, 255)   # #BE9063
DARK = (33, 38, 36, 255)       # #212624
CLEAR = (0, 0, 0, 0)
SIZES = (16, 32, 48, 64, 128, 256)
SS = 3  # supermuestreo por eje


def _rounded(x, y, x0, y0, x1, y1, r) -> bool:
    cx, cy = (x0 + x1) / 2, (y0 + y1) / 2
    hw, hh = (x1 - x0) / 2, (y1 - y0) / 2
    qx = abs(x - cx) - (hw - r)
    qy = abs(y - cy) - (hh - r)
    d = ((max(qx, 0) ** 2 + max(qy, 0) ** 2) ** 0.5) + min(max(qx, qy), 0) - r
    return d <= 0


def _color_at(u: float, v: float):
    if not _rounded(u, v, 0.0, 0.0, 1.0, 1.0, 0.22):
        return CLEAR
    # bolsa
    if _rounded(u, v, 0.30, 0.44, 0.70, 0.86, 0.06):
        return DARK
    # asa (anillo superior)
    dist = ((u - 0.5) ** 2 + (v - 0.44) ** 2) ** 0.5
    if v <= 0.45 and abs(dist - 0.135) <= 0.028:
        return DARK
    return ACCENT


def render(size: int):
    rows = []
    step = 1.0 / (size * SS)
    for py in range(size):
        row = []
        for px in range(size):
            acc = [0.0, 0.0, 0.0, 0.0]
            for sy in range(SS):
                for sx in range(SS):
                    u = (px * SS + sx + 0.5) * step
                    v = (py * SS + sy + 0.5) * step
                    r, g, b, a = _color_at(u, v)
                    w = a / 255.0
                    acc[0] += r * w; acc[1] += g * w; acc[2] += b * w; acc[3] += a
            n = SS * SS
            alpha = acc[3] / n
            if alpha <= 0:
                row.append((0, 0, 0, 0))
            else:
                wsum = acc[3] / 255.0
                row.append((int(acc[0] / wsum), int(acc[1] / wsum), int(acc[2] / wsum), int(alpha)))
        rows.append(row)
    return rows


def _chunk(tag: bytes, data: bytes) -> bytes:
    return struct.pack(">I", len(data)) + tag + data + struct.pack(">I", zlib.crc32(tag + data) & 0xFFFFFFFF)


def encode_png(w: int, h: int, rows) -> bytes:
    raw = b"".join(b"\x00" + bytes(v for px in row for v in px) for row in rows)
    return (
        b"\x89PNG\r\n\x1a\n"
        + _chunk(b"IHDR", struct.pack(">IIBBBBB", w, h, 8, 6, 0, 0, 0))
        + _chunk(b"IDAT", zlib.compress(raw, 9))
        + _chunk(b"IEND", b"")
    )


def build_ico(images) -> bytes:
    header = struct.pack("<HHH", 0, 1, len(images))
    offset = 6 + 16 * len(images)
    entries, blob = b"", b""
    for size, png in images:
        dim = 0 if size >= 256 else size
        entries += struct.pack("<BBBBHHII", dim, dim, 0, 0, 1, 32, len(png), offset + len(blob))
        blob += png
    return header + entries + blob


def main() -> None:
    out_dir = Path(__file__).resolve().parent.parent / "assets"
    out_dir.mkdir(parents=True, exist_ok=True)
    images = []
    for size in SIZES:
        rows = render(size)
        png = encode_png(size, size, rows)
        images.append((size, png))
        if size == 256:
            (out_dir / "icon.png").write_bytes(png)
    (out_dir / "icon.ico").write_bytes(build_ico(images))
    print(f"Generado: {out_dir / 'icon.ico'} ({len(SIZES)} tamanos)")


if __name__ == "__main__":
    main()
