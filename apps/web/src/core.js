// Cargador del nucleo compartido (browser). En Node el self-test lee los JSON con fs.
const CANDIDATES = ["../../core", "../core", "./core", "/core"];

async function findBase() {
  if (location.protocol === "file:") return CANDIDATES[0];
  for (const base of CANDIDATES) {
    try {
      const res = await fetch(`${base}/schema.json`, { method: "HEAD" });
      if (res.ok) return base;
    } catch { /* prueba el siguiente */ }
  }
  return CANDIDATES[0];
}

export async function loadCore(base = null) {
  const root = base || (await findBase());
  const get = async (p) => {
    const res = await fetch(`${root}/${p}`);
    if (!res.ok) throw new Error(`No se pudo cargar ${p} (${res.status})`);
    return res.json();
  };
  return {
    schema: await get("schema.json"),
    rules: await get("rules.json"),
    tokens: await get("tokens.json"),
    seed: await get("seed.json"),
    i18n: { es: await get("i18n/es.json"), en: await get("i18n/en.json") },
    cases: await get("cases/validation.json"),
  };
}
