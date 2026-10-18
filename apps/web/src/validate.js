// Motor de reglas compartido (web). Espejo exacto de apps/desktop/src/equipate/validate.py
// No depende del navegador: recibe `rules` como parámetro.

const EMAIL_RE = /^[^\s@]+@[^\s@]+\.[A-Za-z]{2,}$/;

function isEmpty(v) {
  return v === null || v === undefined || v === "";
}

function isInteger(v) {
  if (typeof v === "number") return Number.isInteger(v);
  return /^-?\d+$/.test(String(v).trim());
}

function isNumeric(v) {
  return !isEmpty(v) && !isNaN(Number(v));
}

/** Valida un campo según su especificación. Devuelve [{key, params}]. */
export function validateField(spec, value, allValues = {}) {
  const errors = [];
  const add = (key, params = {}) => errors.push({ key, params });

  if (spec.required && isEmpty(value)) {
    add("required");
    return errors;
  }
  if (isEmpty(value)) return errors;

  switch (spec.type) {
    case "email":
      if (!EMAIL_RE.test(String(value))) add("invalidEmail");
      break;
    case "integer":
      if (!isInteger(value)) { add("notInteger"); return errors; }
      break;
    case "number":
      if (!isNumeric(value)) { add("notNumber"); return errors; }
      break;
    case "enum":
      if (!spec.values.includes(value)) add("notInEnum");
      break;
    case "confirm":
      if (allValues[spec.matchField] !== value) add("passwordsMismatch");
      return errors;
    default:
      break;
  }

  if (spec.pattern && !new RegExp(spec.pattern).test(String(value))) add("invalidPattern");
  if (spec.minLength !== undefined && String(value).length < spec.minLength) add("minLength", { n: spec.minLength });
  if (spec.maxLength !== undefined && String(value).length > spec.maxLength) add("maxLength", { n: spec.maxLength });
  if (spec.minValue !== undefined && Number(value) < spec.minValue) add("minValue", { n: spec.minValue });
  if (spec.maxValue !== undefined && Number(value) > spec.maxValue) add("maxValue", { n: spec.maxValue });

  return errors;
}

/** Valida todas las reglas de una entidad. Devuelve [{field, key, params}]. */
export function validateEntity(rules, entity, values) {
  const specs = (rules.validation && rules.validation[entity]) || {};
  const out = [];
  for (const field of Object.keys(specs)) {
    for (const e of validateField(specs[field], values[field], values)) {
      out.push({ field, key: e.key, params: e.params });
    }
  }
  return out;
}

/** Reglas de negocio del carrito. */
export function validateCart(rules, cart, productsById) {
  const b = rules.business.carrito;
  const out = [];
  if (cart.length > b.maxItems) out.push({ key: "maxItems", params: { n: b.maxItems } });
  for (const item of cart) {
    const p = productsById[item.id_producto];
    const max = Math.min(b.maxCantidadPorItem, p ? p.stock : Infinity);
    if (item.cantidad > max) {
      out.push({ key: "insufficientStock", field: p ? p.nombre : String(item.id_producto), params: { n: p ? p.stock : 0 } });
    }
  }
  return out;
}

/** Formatea un error usando el diccionario i18n (errors). */
export function formatError(i18n, err, fieldLabel) {
  const tpl = (i18n.errors && i18n.errors[err.key]) || err.key;
  return tpl
    .replace("{field}", fieldLabel ?? err.field ?? "")
    .replace("{n}", err.params && err.params.n !== undefined ? err.params.n : "")
    .replace("{value}", err.params && err.params.value !== undefined ? err.params.value : "");
}
