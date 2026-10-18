// Internacionalizacion ES/EN con claves separadas por puntos (espejo de i18n.py).
export class I18n {
  constructor(dictionaries, lang = "es") {
    this.dictionaries = dictionaries;
    this.lang = lang in dictionaries ? lang : "es";
  }

  setLang(lang) {
    if (lang in this.dictionaries) this.lang = lang;
  }

  toggle() {
    this.lang = this.lang === "es" ? "en" : "es";
    return this.lang;
  }

  raw(key) {
    let node = this.dictionaries[this.lang];
    for (const part of key.split(".")) {
      if (node == null || typeof node !== "object" || !(part in node)) return key;
      node = node[part];
    }
    return node;
  }

  t(key, params = {}) {
    let text = this.raw(key);
    if (typeof text !== "string") return key;
    for (const [name, value] of Object.entries(params)) {
      text = text.split(`{${name}}`).join(String(value));
    }
    return text;
  }

  errors() {
    return this.dictionaries[this.lang].errors || {};
  }
}
