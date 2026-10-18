// App web standalone: sin build, sin dependencias, mobile-first.
import { loadCore } from "./core.js";
import { I18n } from "./i18n.js";
import { Store } from "./store.js";
import { applyTheme, otherTheme } from "./theme.js";
import { validateEntity, validateCart, formatError } from "./validate.js";

const S = { core: null, store: null, i18n: null, theme: "dark", user: null, cart: [], view: "login" };

const t = (key, params) => S.i18n.t(key, params);
const esc = (v) => String(v ?? "").replace(/[&<>"']/g, (c) => ({ "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#39;" }[c]));
const money = (v) => `${Number(v).toLocaleString()} ${t("currency.symbol")}`;
const $ = (sel) => document.querySelector(sel);

const SUN = `<svg viewBox="0 0 24 24" width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="4"/><path d="M12 2v2M12 20v2M2 12h2M20 12h2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M19.1 4.9l-1.4 1.4M6.3 17.7l-1.4 1.4"/></svg>`;
const MOON = `<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>`;

/** Tabla segura para movil: envuelta para permitir scroll horizontal. */
function table(headers, rows) {
  return `<div class="table-wrap"><table>
    <thead><tr>${headers.map((h) => `<th>${h}</th>`).join("")}</tr></thead>
    <tbody>${rows.map((r) => `<tr>${r.map((c) => `<td>${c}</td>`).join("")}</tr>`).join("")}</tbody>
  </table></div>`;
}

function errText(err, field) {
  const label = t(`fields.${field || err.field}`);
  return formatError(S.i18n.dictionaries[S.i18n.lang], err, label.startsWith("fields.") ? field || err.field || "" : label);
}

function toast(message, kind = "info") {
  const box = $("#toast");
  box.className = `toast ${kind}`;
  box.textContent = message;
  if (message) setTimeout(() => { if (box.textContent === message) box.textContent = ""; }, 4000);
}

// ---------------------------------------------------------------- navegacion
function renderNav() {
  const logged = !!S.user;
  const isAdmin = logged && S.user.nivel === "admin";
  const links = [
    ["catalog", "nav.catalog", logged],
    ["cart", "nav.cart", logged],
    ["invoices", "nav.invoices", logged],
    ["admin", "nav.admin", isAdmin],
  ];
  const es = S.i18n.lang === "es";
  const target = otherTheme(S.theme);
  $("#nav").innerHTML = `
    <div class="brand">${esc(t("app.name"))}<span class="tagline">${esc(t("app.tagline"))}</span></div>
    <div class="session">
      <button id="lang" class="mini-btn" title="${esc(t("theme.language"))}" aria-label="${esc(t("theme.language"))}">
        <span class="${es ? "on" : ""}">ES</span><span class="slash">/</span><span class="${es ? "" : "on"}">EN</span>
      </button>
      <button id="theme" class="mini-btn" title="${esc(t(`theme.${target}`))}" aria-label="${esc(t(`theme.${target}`))}">
        ${S.theme === "dark" ? SUN : MOON}
      </button>
      ${logged ? `<button id="logout" class="mini-btn">${esc(t("nav.logout"))}</button>` : ""}
    </div>
    <div class="links">
      ${links.filter(([, , on]) => on).map(([v, k]) => `<button data-view="${v}" class="${S.view === v ? "active" : ""}">${esc(t(k))}</button>`).join("")}
      ${logged ? `<span class="who">${esc(t("messages.session_user", { usuario: S.user.usuario }))}</span>` : ""}
    </div>`;
  $("#nav").querySelectorAll("[data-view]").forEach((b) => b.addEventListener("click", () => go(b.dataset.view)));
  $("#lang").addEventListener("click", toggleLang);
  $("#theme").addEventListener("click", toggleTheme);
  const logout = $("#logout");
  if (logout) logout.addEventListener("click", doLogout);
}

function go(view) {
  const needsLogin = ["catalog", "cart", "invoices"].includes(view);
  const needsAdmin = view === "admin";
  if (needsLogin && !S.user) return toast(t("messages.login_required"), "error");
  if (needsAdmin && (!S.user || S.user.nivel !== "admin")) return toast(t("messages.login_required"), "error");
  S.view = view;
  render();
}

function toggleLang() {
  S.i18n.toggle();
  localStorage.setItem("lang", S.i18n.lang);
  render();
}

function toggleTheme() {
  S.theme = otherTheme(S.theme);
  localStorage.setItem("theme", S.theme);
  applyTheme(S.core.tokens, S.theme);
  render();
}

// -------------------------------------------------------------------- login
function viewLogin() {
  $("#view").innerHTML = `
    <section class="card narrow">
      <h1>${esc(t("messages.welcome"))}</h1>
      <form id="login-form">
        <label>${esc(t("fields.usuario"))}<input name="usuario" autocomplete="username"></label>
        <label>${esc(t("fields.contrasena"))}<input name="contrasena" type="password" autocomplete="current-password"></label>
        <button class="primary" type="submit">${esc(t("actions.ingresar"))}</button>
      </form>
      <p class="hint">jfelya / admin123 &nbsp;·&nbsp; josecas / cliente1</p>
    </section>`;
  $("#login-form").addEventListener("submit", doLogin);
}

async function doLogin(ev) {
  ev.preventDefault();
  const f = ev.target;
  const values = { usuario: f.usuario.value.trim().toLowerCase(), contrasena: f.contrasena.value };
  const errors = validateEntity(S.core.rules, "usuario", values).filter((e) => ["usuario", "contrasena"].includes(e.field));
  if (errors.length) return toast(errText(errors[0]), "error");
  const user = await S.store.authenticate(values.usuario, values.contrasena);
  if (!user) return toast(t("errors.invalidCredentials"), "error");
  S.user = user;
  S.cart = [];
  toast(t("messages.session_user", { usuario: user.usuario }), "ok");
  go("catalog");
}

function doLogout() {
  S.user = null;
  S.cart = [];
  toast(t("messages.logout_ok"), "muted");
  go("login");
}

// ------------------------------------------------------------------ catalogo
async function viewCatalog() {
  const products = await S.store.productos();
  const rows = products.map((p) => [
    esc(p.nombre), p.stock, money(p.precio),
    `<button class="primary" data-add="${p.id}">${esc(t("actions.agregar"))}</button>`,
  ]);
  $("#view").innerHTML = `
    <section class="card">
      <h1>${esc(t("table.productos"))}</h1>
      ${table([esc(t("fields.producto")), esc(t("fields.stock")), esc(t("fields.precio")), esc(t("fields.acciones"))], rows)}
    </section>`;
  $("#view").querySelectorAll("[data-add]").forEach((b) => b.addEventListener("click", () => addToCart(Number(b.dataset.add), products)));
}

function addToCart(pid, products) {
  const product = products.find((p) => p.id === pid);
  const existing = S.cart.find((i) => i.id_producto === pid);
  if (existing) existing.cantidad += 1;
  else S.cart.push({ id_producto: pid, nombre: product.nombre, precio: product.precio, cantidad: 1 });
  const byId = Object.fromEntries(products.map((p) => [p.id, p]));
  const errors = validateCart(S.core.rules, S.cart, byId);
  if (errors.length) {
    if (existing) existing.cantidad -= 1; else S.cart.pop();
    return toast(errText(errors[0]), "error");
  }
  toast(t("messages.saved_ok"), "ok");
}

// -------------------------------------------------------------------- carrito
function viewCart() {
  const total = S.cart.reduce((s, i) => s + i.precio * i.cantidad, 0);
  const rows = S.cart.map((i, idx) => [
    esc(i.nombre), money(i.precio), i.cantidad,
    `<button data-qty="${idx}" data-d="-1" aria-label="${esc(t("actions.quitar"))}">−</button>
     <button data-qty="${idx}" data-d="1">+</button>
     <button class="danger" data-del="${idx}">${esc(t("actions.quitar"))}</button>`,
  ]);
  $("#view").innerHTML = `
    <section class="card">
      <h1>${esc(t("table.carrito", { usuario: S.user.usuario }))}</h1>
      ${S.cart.length === 0 ? `<p class="muted">${esc(t("messages.empty_cart"))}</p>` : `
        ${table([esc(t("fields.producto")), esc(t("fields.precio")), esc(t("fields.cantidad")), esc(t("fields.acciones"))], rows)}
        <p class="total">${esc(t("fields.total"))}: ${money(total)}</p>
        <div class="row">
          <button class="primary" id="buy">${esc(t("actions.comprar"))}</button>
          <button id="clear">${esc(t("actions.vaciar"))}</button>
        </div>`}
    </section>`;
  $("#view").querySelectorAll("[data-qty]").forEach((b) => b.addEventListener("click", () => changeQty(Number(b.dataset.qty), Number(b.dataset.d))));
  $("#view").querySelectorAll("[data-del]").forEach((b) => b.addEventListener("click", () => { S.cart.splice(Number(b.dataset.del), 1); render(); }));
  const clear = $("#clear");
  if (clear) clear.addEventListener("click", () => { S.cart = []; render(); });
  const buy = $("#buy");
  if (buy) buy.addEventListener("click", doCheckout);
}

async function changeQty(idx, delta) {
  S.cart[idx].cantidad += delta;
  if (S.cart[idx].cantidad < 1) {
    S.cart.splice(idx, 1);
  } else {
    const products = await S.store.productos();
    const byId = Object.fromEntries(products.map((p) => [p.id, p]));
    const errors = validateCart(S.core.rules, S.cart, byId);
    if (errors.length) { S.cart[idx].cantidad -= delta; return toast(errText(errors[0]), "error"); }
  }
  render();
}

async function doCheckout() {
  if (!S.cart.length) return toast(t("errors.emptyCart"), "error");
  const products = await S.store.productos();
  const byId = Object.fromEntries(products.map((p) => [p.id, p]));
  const errors = validateCart(S.core.rules, S.cart, byId);
  if (errors.length) return toast(errText(errors[0]), "error");
  const id = await S.store.checkout(S.user.id, S.cart);
  S.cart = [];
  toast(t("messages.purchase_ok", { id }), "ok");
  go("invoices");
}

// ------------------------------------------------------------------- facturas
async function viewInvoices() {
  const invoices = await S.store.facturasByCliente(S.user.id);
  const rows = invoices.map((inv) => [
    inv.id, esc(inv.fecha.slice(0, 10)), money(inv.preciototal),
    `<button data-det="${inv.id}">${esc(t("actions.ver_detalle"))}</button>`,
  ]);
  $("#view").innerHTML = `
    <section class="card">
      <h1>${esc(t("table.facturas", { usuario: S.user.usuario }))}</h1>
      ${invoices.length === 0 ? `<p class="muted">${esc(t("messages.empty_invoices"))}</p>` : `
        ${table(["#", esc(t("fields.fecha")), esc(t("fields.total")), esc(t("fields.acciones"))], rows)}`}
      <div id="detail"></div>
    </section>`;
  $("#view").querySelectorAll("[data-det]").forEach((b) => b.addEventListener("click", () => showDetail(Number(b.dataset.det))));
}

async function showDetail(idFactura) {
  const rows = (await S.store.detalleByFactura(idFactura)).map((d) => [esc(d.nombre_producto), d.cantidad, money(d.precio)]);
  $("#detail").innerHTML = `
    <h2>${esc(t("table.detalle_factura", { id: idFactura }))}</h2>
    ${table([esc(t("fields.producto")), esc(t("fields.cantidad")), esc(t("fields.precio"))], rows)}`;
}

// ---------------------------------------------------------------------- admin
async function viewAdmin() {
  const products = await S.store.productos();
  const preguntas = S.core.rules.validation.usuario.pregunta_secreta.values;
  const rows = products.map((p) => [
    esc(p.nombre), p.stock, money(p.precio),
    `<button class="danger" data-delp="${p.id}">${esc(t("actions.borrar"))}</button>`,
  ]);
  $("#view").innerHTML = `
    <section class="card">
      <h1>${esc(t("nav.admin"))}</h1>
      <div class="cols">
        <form id="product-form">
          <h2>${esc(t("admin.new_product"))}</h2>
          <label>${esc(t("fields.nombre"))}<input name="nombre"></label>
          <label>${esc(t("fields.stock"))}<input name="stock" type="number"></label>
          <label>${esc(t("fields.precio"))}<input name="precio" type="number"></label>
          <button class="primary" type="submit">${esc(t("actions.registrar"))}</button>
        </form>
        <form id="user-form">
          <h2>${esc(t("admin.new_user"))}</h2>
          <label>${esc(t("fields.nombre"))}<input name="nombre"></label>
          <label>${esc(t("fields.apellido"))}<input name="apellido"></label>
          <label>${esc(t("fields.usuario"))}<input name="usuario"></label>
          <label>${esc(t("fields.correo"))}<input name="correo" type="email"></label>
          <label>${esc(t("fields.contrasena"))}<input name="contrasena" type="password"></label>
          <label>${esc(t("fields.contrasena2"))}<input name="contrasena2" type="password"></label>
          <label>${esc(t("fields.nivel"))}<select name="nivel"><option>cliente</option><option>admin</option></select></label>
          <label>${esc(t("fields.pregunta_secreta"))}<select name="pregunta_secreta">${preguntas.map((q) => `<option>${esc(q)}</option>`).join("")}</select></label>
          <label>${esc(t("fields.respuesta_secreta"))}<input name="respuesta_secreta"></label>
          <button class="primary" type="submit">${esc(t("actions.registrar"))}</button>
        </form>
      </div>
      <h2>${esc(t("admin.products"))}</h2>
      ${table([esc(t("fields.producto")), esc(t("fields.stock")), esc(t("fields.precio")), ""], rows)}
    </section>`;
  $("#product-form").addEventListener("submit", registerProduct);
  $("#user-form").addEventListener("submit", registerUser);
  $("#view").querySelectorAll("[data-delp]").forEach((b) => b.addEventListener("click", async () => { await S.store.deleteProducto(Number(b.dataset.delp)); render(); }));
}

async function registerProduct(ev) {
  ev.preventDefault();
  const f = ev.target;
  const values = { nombre: f.nombre.value.trim(), stock: f.stock.value.trim(), precio: f.precio.value.trim() };
  const errors = validateEntity(S.core.rules, "producto", values);
  if (errors.length) return toast(errText(errors[0]), "error");
  if (await S.store.productoByNombre(values.nombre)) return toast(t("errors.productExists", { value: values.nombre }), "error");
  await S.store.insertProducto(values);
  toast(t("messages.saved_ok"), "ok");
  render();
}

async function registerUser(ev) {
  ev.preventDefault();
  const f = ev.target;
  const values = Object.fromEntries(new FormData(f).entries());
  values.usuario = values.usuario.trim().toLowerCase();
  values.respuesta_secreta = values.respuesta_secreta.trim().toLowerCase();
  const errors = validateEntity(S.core.rules, "usuario", values);
  if (errors.length) return toast(errText(errors[0]), "error");
  if (await S.store.usuarioByUsuario(values.usuario)) return toast(t("errors.userExists", { value: values.usuario }), "error");
  await S.store.insertUsuario(values);
  f.reset();
  toast(t("messages.saved_ok"), "ok");
}

// ------------------------------------------------------------------ arranque
function render() {
  renderNav();
  switch (S.view) {
    case "catalog": viewCatalog(); break;
    case "cart": viewCart(); break;
    case "invoices": viewInvoices(); break;
    case "admin": viewAdmin(); break;
    default: viewLogin();
  }
}

async function main() {
  S.core = await loadCore();
  S.store = await Store.open(S.core.schema, S.core.seed);
  S.i18n = new I18n(S.core.i18n, localStorage.getItem("lang") || "es");
  S.theme = localStorage.getItem("theme") || "dark";
  applyTheme(S.core.tokens, S.theme);
  render();
}

main().catch((err) => { console.error(err); toast(String(err), "error"); });
