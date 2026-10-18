// Cargador del nucleo compartido.
// El nucleo (core/*.json) lo inyecta BaseLayout.astro en tiempo de compilacion,
// asi el cliente no hace fetch y funciona incluso abriendo el HTML directamente.
export async function loadCore() {
  if (!globalThis.__CORE__) {
    throw new Error("Nucleo no inicializado (falta window.__CORE__).");
  }
  return globalThis.__CORE__;
}
