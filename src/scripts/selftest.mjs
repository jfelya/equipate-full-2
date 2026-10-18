// Self-test web (Node): verifica que el motor de reglas cumple core/cases.
// Espejo de apps/desktop/src/selftest.py. La logica de IndexedDB (store.js) se
// prueba en el navegador; aqui se cubre el contrato de validacion.
import { readFileSync } from "node:fs";
import { fileURLToPath } from "node:url";
import { validateEntity } from "./validate.js";

const coreDir = new URL("../../core/", import.meta.url);
const read = (p) => JSON.parse(readFileSync(fileURLToPath(new URL(p, coreDir)), "utf-8"));

const rules = read("rules.json");
const cases = read("cases/validation.json");

let passed = 0;
let failed = 0;

function check(name, got, expected) {
  const a = JSON.stringify(got);
  const b = JSON.stringify(expected);
  if (a === b) { passed += 1; console.log(`  PASS  ${name}`); }
  else { failed += 1; console.log(`  FAIL  ${name}\n        esperado=${b}\n        obtenido=${a}`); }
}

console.log("Validacion por campo:");
for (const c of cases.cases) {
  const errors = validateEntity(rules, c.entity, { [c.field]: c.input });
  check(c.name, errors.filter((e) => e.field === c.field).map((e) => e.key), c.expect);
}

console.log("Validacion de formulario:");
for (const c of cases.formCases) {
  const errors = validateEntity(rules, c.entity, c.values);
  check(`${c.name} (campos)`, [...new Set(errors.map((e) => e.field))].sort(), [...c.expectFields].sort());
  check(`${c.name} (claves)`, [...new Set(errors.map((e) => e.key))].sort(), [...c.expectKeys].sort());
}

console.log("Checkout (aritmetica):");
for (const c of cases.checkoutCases) {
  const total = c.cart.reduce((s, item) => s + c.product.precio * item.cantidad, 0);
  const stockAfter = c.product.stock - c.cart.reduce((s, item) => s + item.cantidad, 0);
  check(`${c.name} (total)`, total, c.expectTotal);
  check(`${c.name} (stock)`, stockAfter, c.expectStockAfter);
}

console.log(`\n${passed} passed, ${failed} failed`);
process.exit(failed ? 1 : 0);
