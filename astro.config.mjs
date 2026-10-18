// Estructura segun la guia de Astro: https://docs.astro.build/es/basics/project-structure/
import { defineConfig } from "astro/config";

export default defineConfig({
  // Sitio 100% estatico: sin servidor ni backend.
  output: "static",
  // trailingSlash 'ignore' para que / y /index.html funcionen igual.
  trailingSlash: "ignore",
  build: {
    // Los assets del cliente van a /_astro/.
    assets: "_astro",
  },
  devToolbar: { enabled: false },
});
