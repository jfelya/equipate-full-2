"""Motor de reglas compartido (escritorio).

Espejo exacto de apps/web/src/validate.js.
"""
from __future__ import annotations

import re

EMAIL_RE = re.compile(r"^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$")
INT_RE = re.compile(r"^-?\d+$")


def _is_empty(v) -> bool:
    return v is None or v == ""


def _is_integer(v) -> bool:
    if isinstance(v, bool):
        return False
    if isinstance(v, int):
        return True
    if isinstance(v, float):
        return v.is_integer()
    return bool(INT_RE.match(str(v).strip()))


def _is_numeric(v) -> bool:
    if _is_empty(v):
        return False
    try:
        float(v)
        return True
    except (TypeError, ValueError):
        return False


def validate_field(spec: dict, value, all_values: dict | None = None) -> list[dict]:
    """Valida un campo. Devuelve [{'key':..., 'params':{...}}]."""
    all_values = all_values or {}
    errors: list[dict] = []

    def add(key, params=None):
        errors.append({"key": key, "params": params or {}})

    if spec.get("required") and _is_empty(value):
        add("required")
        return errors
    if _is_empty(value):
        return errors

    ftype = spec.get("type", "string")
    if ftype == "email":
        if not EMAIL_RE.match(str(value)):
            add("invalidEmail")
    elif ftype == "integer":
        if not _is_integer(value):
            add("notInteger")
            return errors
    elif ftype == "number":
        if not _is_numeric(value):
            add("notNumber")
            return errors
    elif ftype == "enum":
        if value not in spec.get("values", []):
            add("notInEnum")
    elif ftype == "confirm":
        if all_values.get(spec.get("matchField")) != value:
            add("passwordsMismatch")
        return errors

    if spec.get("pattern") and not re.search(spec["pattern"], str(value)):
        add("invalidPattern")
    if "minLength" in spec and len(str(value)) < spec["minLength"]:
        add("minLength", {"n": spec["minLength"]})
    if "maxLength" in spec and len(str(value)) > spec["maxLength"]:
        add("maxLength", {"n": spec["maxLength"]})
    if "minValue" in spec and float(value) < spec["minValue"]:
        add("minValue", {"n": spec["minValue"]})
    if "maxValue" in spec and float(value) > spec["maxValue"]:
        add("maxValue", {"n": spec["maxValue"]})

    return errors


def validate_entity(rules: dict, entity: str, values: dict) -> list[dict]:
    """Valida todas las reglas de una entidad. Devuelve [{'field','key','params'}]."""
    specs = (rules.get("validation") or {}).get(entity, {})
    out: list[dict] = []
    for field, spec in specs.items():
        for e in validate_field(spec, values.get(field), values):
            out.append({"field": field, "key": e["key"], "params": e["params"]})
    return out


def validate_cart(rules: dict, cart: list[dict], products_by_id: dict) -> list[dict]:
    """Reglas de negocio del carrito."""
    b = rules["business"]["carrito"]
    out: list[dict] = []
    if len(cart) > b["maxItems"]:
        out.append({"key": "maxItems", "params": {"n": b["maxItems"]}})
    for item in cart:
        p = products_by_id.get(item["id_producto"])
        stock = p["stock"] if p else None
        max_qty = b["maxCantidadPorItem"] if stock is None else min(b["maxCantidadPorItem"], stock)
        if item["cantidad"] > max_qty:
            out.append({
                "key": "insufficientStock",
                "field": p["nombre"] if p else str(item["id_producto"]),
                "params": {"n": stock if stock is not None else 0},
            })
    return out


def format_error(i18n: dict, err: dict, field_label: str | None = None) -> str:
    """Formatea un error con el diccionario i18n."""
    tpl = (i18n.get("errors") or {}).get(err["key"], err["key"])
    params = err.get("params") or {}
    return (
        tpl.replace("{field}", field_label or err.get("field") or "")
        .replace("{n}", str(params.get("n", "")))
        .replace("{value}", str(params.get("value", "")))
    )
