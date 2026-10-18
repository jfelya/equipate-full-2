// Persistencia en IndexedDB. Espejo de apps/desktop/src/equipate/store.py (SQLite).
const DB_NAME = "equipate_full";
const DB_VERSION = 1;

export async function sha256(text) {
  const buf = await crypto.subtle.digest("SHA-256", new TextEncoder().encode(text));
  return [...new Uint8Array(buf)].map((b) => b.toString(16).padStart(2, "0")).join("");
}

const req = (r) => new Promise((resolve, reject) => {
  r.onsuccess = () => resolve(r.result);
  r.onerror = () => reject(r.error);
});

const txDone = (tx) => new Promise((resolve, reject) => {
  tx.oncomplete = () => resolve();
  tx.onerror = () => reject(tx.error);
});

function nowIso() {
  return new Date().toISOString().slice(0, 19) + "Z";
}

export class Store {
  constructor(db, schema) {
    this.db = db;
    this.schema = schema;
  }

  static async open(schema, seed) {
    const db = await new Promise((resolve, reject) => {
      const request = indexedDB.open(DB_NAME, DB_VERSION);
      request.onupgradeneeded = () => {
        const idb = request.result;
        for (const [, meta] of Object.entries(schema.entities)) {
          if (idb.objectStoreNames.contains(meta.table)) continue;
          const store = idb.createObjectStore(meta.table, { keyPath: meta.primaryKey, autoIncrement: !!meta.autoIncrement });
          for (const [field, spec] of Object.entries(meta.fields)) {
            if (field === meta.primaryKey) continue;
            if (spec.unique || spec.references) store.createIndex(field, field, { unique: false });
          }
        }
      };
      request.onsuccess = () => resolve(request.result);
      request.onerror = () => reject(request.error);
    });
    const store = new Store(db, schema);
    if (seed) await store.seedIfEmpty(seed);
    return store;
  }

  table(entity) {
    return this.schema.entities[entity].table;
  }

  async all(entity) {
    const tx = this.db.transaction(this.table(entity), "readonly");
    return req(tx.objectStore(this.table(entity)).getAll());
  }

  async byId(entity, id) {
    const tx = this.db.transaction(this.table(entity), "readonly");
    return req(tx.objectStore(this.table(entity)).get(id));
  }

  async byIndex(entity, field, value) {
    const tx = this.db.transaction(this.table(entity), "readonly");
    const index = tx.objectStore(this.table(entity)).index(field);
    return req(index.get(value));
  }

  async add(entity, data) {
    const tx = this.db.transaction(this.table(entity), "readwrite");
    const id = await req(tx.objectStore(this.table(entity)).add(data));
    await txDone(tx);
    return id;
  }

  async put(entity, data) {
    const tx = this.db.transaction(this.table(entity), "readwrite");
    await req(tx.objectStore(this.table(entity)).put(data));
    await txDone(tx);
  }

  async remove(entity, id) {
    const tx = this.db.transaction(this.table(entity), "readwrite");
    await req(tx.objectStore(this.table(entity)).delete(id));
    await txDone(tx);
  }

  async seedIfEmpty(seed) {
    if ((await this.all("usuario")).length === 0) {
      for (const u of seed.usuarios) {
        await this.add("usuario", { ...u, contrasena_hash: u.contrasena_hash || (await sha256(u.contrasena || "")), creado: nowIso() });
      }
    }
    if ((await this.all("producto")).length === 0) {
      for (const p of seed.productos) await this.add("producto", { ...p, creado: nowIso() });
    }
  }

  // --- dominio ---------------------------------------------------------
  productos() {
    return this.all("producto");
  }

  async insertProducto(data) {
    return this.add("producto", { nombre: data.nombre, stock: Number(data.stock), precio: Number(data.precio), creado: nowIso() });
  }

  productoByNombre(nombre) {
    return this.byIndex("producto", "nombre", nombre);
  }

  deleteProducto(id) {
    return this.remove("producto", id);
  }

  usuarios() {
    return this.all("usuario");
  }

  usuarioByUsuario(usuario) {
    return this.byIndex("usuario", "usuario", usuario);
  }

  async insertUsuario(data) {
    const { contrasena, contrasena2, ...rest } = data;
    return this.add("usuario", { ...rest, usuario: data.usuario.toLowerCase(), respuesta_secreta: String(data.respuesta_secreta).toLowerCase(), contrasena_hash: await sha256(contrasena), creado: nowIso() });
  }

  async authenticate(usuario, contrasena) {
    const user = await this.usuarioByUsuario(String(usuario).toLowerCase().trim());
    if (user && user.contrasena_hash === (await sha256(contrasena))) return user;
    return null;
  }

  async facturasByCliente(idCliente) {
    const rows = await this.all("factura");
    return rows.filter((f) => f.id_cliente === idCliente).sort((a, b) => b.id - a.id);
  }

  async detalleByFactura(idFactura) {
    const rows = await this.all("detalle");
    return rows.filter((d) => d.id_factura === idFactura);
  }

  async checkout(idCliente, cart) {
    const total = cart.reduce((sum, i) => sum + i.precio * i.cantidad, 0);
    const tx = this.db.transaction([this.table("factura"), this.table("detalle"), this.table("producto")], "readwrite");
    const facturas = tx.objectStore(this.table("factura"));
    const detalles = tx.objectStore(this.table("detalle"));
    const productos = tx.objectStore(this.table("producto"));
    const idFactura = await req(facturas.add({ id_cliente: idCliente, fecha: nowIso(), preciototal: total }));
    for (const item of cart) {
      detalles.add({ id_factura: idFactura, id_producto: item.id_producto, nombre_producto: item.nombre, cantidad: item.cantidad, precio: item.precio });
      const product = await req(productos.get(item.id_producto));
      product.stock -= item.cantidad;
      productos.put(product);
    }
    await txDone(tx);
    return idFactura;
  }
}
