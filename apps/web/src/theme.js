// Tema desde core/tokens.json aplicado como variables CSS.
export function applyTheme(tokens, name) {
  const c = tokens.themes[name];
  const root = document.documentElement;
  root.dataset.theme = name;
  for (const [key, value] of Object.entries(c)) {
    root.style.setProperty(`--${key}`, value);
  }
  root.style.setProperty("--radius", `${tokens.radius}px`);
  root.style.setProperty("--font", tokens.typography.fontFamily);
  return c;
}

export function otherTheme(name) {
  return name === "dark" ? "light" : "dark";
}
