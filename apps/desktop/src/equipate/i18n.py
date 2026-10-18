"""Internacionalizacion ES/EN con claves separadas por puntos."""
from __future__ import annotations


class I18n:
    def __init__(self, dictionaries: dict, lang: str = "es"):
        self.dictionaries = dictionaries
        self.lang = lang if lang in dictionaries else "es"

    def set_lang(self, lang: str) -> None:
        if lang in self.dictionaries:
            self.lang = lang

    def toggle(self) -> str:
        self.lang = "en" if self.lang == "es" else "es"
        return self.lang

    def raw(self, key: str):
        node = self.dictionaries[self.lang]
        for part in key.split("."):
            if not isinstance(node, dict) or part not in node:
                return key
            node = node[part]
        return node

    def t(self, key: str, **params) -> str:
        text = self.raw(key)
        if not isinstance(text, str):
            return key
        for name, value in params.items():
            text = text.replace("{" + name + "}", str(value))
        return text

    def errors(self) -> dict:
        return self.dictionaries[self.lang].get("errors", {})
