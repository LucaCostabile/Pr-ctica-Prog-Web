class Motor {
  constructor(tipo, caballos, descripcion) {
    this.tipo = tipo;
    this.caballos = Number(caballos) || 0;
    this.descripcion = descripcion || '';
  }
  info() { return `${this.tipo} - ${this.caballos} CV - ${this.descripcion}`; }
}

class Rueda {
  constructor(marca, rodaje) { this.marca = marca || ''; this.rodaje = rodaje || '0 km'; }
  info() { return `${this.marca} (${this.rodaje})`; }
}

class Titulo {
  constructor(propietario = 'Sin propietario') { this.propietario = propietario; }
  info() { return `Propietario: ${this.propietario}`; }
}

class RNA {
  constructor(provincia = '') { this.provincia = provincia; }
  info() { return `RNA - Provincia: ${this.provincia}`; }
}

class Vehiculo {
  // datos privados: marca, modelo, anio, ruedas, titulo, motor, velocidad
  #marca; #modelo; #anio; #ruedas; #titulo; #motor; #velocidad;
  constructor(marca, modelo, anio, ruedas = [], titulo = null, motor = null) {
    this.#marca = marca;
    this.#modelo = modelo;
    this.#anio = anio;
    this.#ruedas = ruedas || [];
    this.#titulo = titulo || new Titulo();
    this.#motor = motor || null; // composición con Motor
    this.#velocidad = 0;
  }
  // getters / setters mínimos para acceder a datos privados
  getMarca() { return this.#marca; }
  getModelo() { return this.#modelo; }
  getAnio() { return this.#anio; }
  getRuedas() { return this.#ruedas; }
  setRuedas(r) { this.#ruedas = r; }
  getTitulo() { return this.#titulo; }
  setTitulo(t) { this.#titulo = t; }
  getMotor() { return this.#motor; }
  setMotor(m) { this.#motor = m; }
  acelerar(delta = 10) { this.#velocidad += delta; return this.#velocidad; }
  frenar() { this.#velocidad = 0; return this.#velocidad; }
  info() { return `${this.#marca} ${this.#modelo} (${this.#anio}) - Vel: ${this.#velocidad} km/h - Ruedas: ${this.#ruedas.length} - ${this.#titulo.info()} - Motor: ${this.#motor ? this.#motor.info() : 'sin motor'}`; }
  compararVelocidad(otro) { return this.#velocidad - (otro?.getVelocidad ? otro.getVelocidad() : 0); }
  getVelocidad() { return this.#velocidad; }
  asignarPropietarioTitulo(nombre) { if (this.#titulo) this.#titulo.propietario = nombre; }
}

class Auto extends Vehiculo {
  constructor(marca, modelo, anio, motor = null, cantPuertas = 4, ruedas = null, titulo = null) {
    const r = ruedas || Array.from({ length: 4 }, () => new Rueda(marca, '0 km'));
    super(marca, modelo, anio, r, titulo, motor);
    this.cantPuertas = Number(cantPuertas) || 4;
  }
  info() { return `Auto: ${super.info()} - Puertas: ${this.cantPuertas}`; }
}

class Camion extends Vehiculo {
  constructor(marca, modelo, anio, motor = null, capacidadCarga = 0, tipoEntidad = null, ruedas = null, titulo = null) {
    const r = ruedas || Array.from({ length: 6 }, () => new Rueda(marca, '0 km'));
    super(marca, modelo, anio, r, titulo, motor);
    this.capacidadCarga = Number(capacidadCarga) || 0;
    this.tipoEntidad = tipoEntidad; // puede ser instancia de Remolque o Frigorifico
    this.cargaActual = 0;
  }
  cargar(kg) {
    const posible = Math.max(0, Number(kg) || 0);
    if (this.cargaActual + posible > this.capacidadCarga) {
      const disponible = this.capacidadCarga - this.cargaActual;
      this.cargaActual = this.capacidadCarga;
      return { cargado: disponible, excede: true };
    }
    this.cargaActual += posible;
    return { cargado: posible, excede: false };
  }
  info() { return `Camión: ${super.info()} - Capacidad: ${this.capacidadCarga} kg - Carga actual: ${this.cargaActual} kg - Tipo: ${this.tipoEntidad ? this.tipoEntidad.info() : 'sin tipo'}`; }
}

class Remolque {
  constructor(descripcion = 'Remolque estándar') { this.descripcion = descripcion; }
  info() { return `Remolque - ${this.descripcion}`; }
}

class Frigorifico {
  constructor(potencia = 'normal') { this.potencia = potencia; }
  info() { return `Frigorífico - Potencia: ${this.potencia}`; }
}

class Concesionario {
  constructor(nombre) { this.nombre = nombre; this.vehiculos = []; }
  agregarVehiculo(v) { if (v && v.asignarPropietarioTitulo) v.asignarPropietarioTitulo(this.nombre); this.vehiculos.push(v); }
  info() { return `${this.nombre} - Vehículos: ${this.vehiculos.length}`; }
}

class Fabrica {
  constructor(nombre) { this.nombre = nombre; }
  construye(tipo, ...args) {
    if (tipo === 'auto') return new Auto(...args);
    if (tipo === 'camion') return new Camion(...args);
    return null;
  }
}

const motores = [];
const ruedasTemplates = [];
const titulos = [];
const vehiculos = [];
let concesionario = null;

function $(id) { return document.getElementById(id); }

function actualizarSelects() {
  const motorSelects = [$('auto-motor'), $('camion-motor')];
  motorSelects.forEach(sel => {
    if (!sel) return;
    sel.innerHTML = '<option value="">-- seleccionar motor --</option>' + motores.map((m, i) => `<option value="${i}">${m.info()}</option>`).join('');
  });
  // actualizar selects de títulos si existen en el DOM
  const tituloSelects = [$('auto-titulo'), $('camion-titulo')];
  tituloSelects.forEach(sel => {
    if (!sel) return;
    sel.innerHTML = '<option value="">-- seleccionar título --</option>' + titulos.map((t, i) => `<option value="${i}">${t.info()}</option>`).join('');
  });

  // actualizar selects/plantillas de rueda
  const ruedaSelects = [$('auto-rueda'), $('camion-rueda')];
  ruedaSelects.forEach(sel => {
    if (!sel) return;
    sel.innerHTML = '<option value="">-- seleccionar plantilla de rueda --</option>' + ruedasTemplates.map((r, i) => `<option value="${i}">${r.info()}</option>`).join('');
  });
}

function renderList(containerId, items, formatter) {
  const cont = $(containerId);
  cont.innerHTML = items.map((it, i) => `<div class="item">${formatter(it,i)}</div>`).join('');
}

document.addEventListener('DOMContentLoaded', () => {

  $('crear-motor').addEventListener('click', () => {
    const m = new Motor($('motor-tipo').value, $('motor-caballos').value, $('motor-desc').value);
    motores.push(m);
    $('motor-tipo').value = $('motor-caballos').value = $('motor-desc').value = '';
    actualizarSelects();
    renderList('motores-list', motores, (m,i) => `${i}: ${m.info()}`);
  });

  // crear plantilla de rueda
  const crearRuedaBtn = $('crear-rueda');
  if (crearRuedaBtn) {
    crearRuedaBtn.addEventListener('click', () => {
      const r = new Rueda($('rueda-marca').value, $('rueda-rodaje').value);
      ruedasTemplates.push(r);
      $('rueda-marca').value = $('rueda-rodaje').value = '';
      renderList('ruedas-list', ruedasTemplates, (r,i) => `${i}: ${r.info()}`);
      actualizarSelects();
    });
  }

  // crear título
  const crearTituloBtn = $('crear-titulo');
  if (crearTituloBtn) {
    crearTituloBtn.addEventListener('click', () => {
      const t = new Titulo($('titulo-propietario').value || 'Sin propietario');
      titulos.push(t);
      $('titulo-propietario').value = '';
      renderList('titulos-list', titulos, (t,i) => `${i}: ${t.info()}`);
      actualizarSelects();
    });
  }


  $('crear-auto').addEventListener('click', () => {
    const motorIdx = $('auto-motor').value;
    const motor = motorIdx !== '' ? motores[Number(motorIdx)] : null;
  const ruedaIdx = $('auto-rueda') ? $('auto-rueda').value : '';
  const tituloIdx = $('auto-titulo') ? $('auto-titulo').value : '';
  const ruedas = (ruedaIdx !== '' && ruedasTemplates[Number(ruedaIdx)]) ? Array.from({ length: 4 }, () => ruedasTemplates[Number(ruedaIdx)]) : null;
  const titulo = (tituloIdx !== '' && titulos[Number(tituloIdx)]) ? titulos[Number(tituloIdx)] : null;
    const a = new Auto($('auto-marca').value, $('auto-modelo').value, $('auto-anio').value, motor, $('auto-puertas').value);
  // si hay ruedas o título seleccionados, asignarlos mediante setters
  if (ruedas) a.setRuedas(ruedas);
  if (titulo) a.setTitulo(titulo);
  if (motor) a.setMotor(motor);
    vehiculos.push(a);
    renderList('vehiculos-list', vehiculos, (v,i) => `${i}: ${v.info()} <button onclick="window.__uiAcelerar(${i})">Acelerar</button> <button onclick="window.__uiFrenar(${i})">Frenar</button> <button onclick="window.__uiAgregar(${i})">Agregar al concesionario</button>`);
  });


  $('crear-camion').addEventListener('click', () => {
    const motorIdx = $('camion-motor').value;
    const motor = motorIdx !== '' ? motores[Number(motorIdx)] : null;
    const tipoSel = $('camion-tipo') ? $('camion-tipo').value : '';
    const ruedaIdx = $('camion-rueda') ? $('camion-rueda').value : '';
    const tituloIdx = $('camion-titulo') ? $('camion-titulo').value : '';
    const ruedas = (ruedaIdx !== '' && ruedasTemplates[Number(ruedaIdx)]) ? Array.from({ length: 6 }, () => ruedasTemplates[Number(ruedaIdx)]) : null;
    const titulo = (tituloIdx !== '' && titulos[Number(tituloIdx)]) ? titulos[Number(tituloIdx)] : null;
    // crear instancia de tipoEntidad según selección
    let tipoEntidad = null;
    if (tipoSel === 'remolque') tipoEntidad = new Remolque();
    if (tipoSel === 'frigorifico') tipoEntidad = new Frigorifico();
    const c = new Camion($('camion-marca').value, $('camion-modelo').value, $('camion-anio').value, motor, $('camion-capacidad').value, tipoEntidad);
    if (ruedas) c.setRuedas(ruedas);
    if (titulo) c.setTitulo(titulo);
    vehiculos.push(c);
    renderList('vehiculos-list', vehiculos, (v,i) => `${i}: ${v.info()} <button onclick="window.__uiAcelerar(${i})">Acelerar</button> <button onclick="window.__uiFrenar(${i})">Frenar</button> <button onclick="window.__uiAgregar(${i})">Agregar al concesionario</button>`);
  });


  $('crear-concesionario').addEventListener('click', () => {
    const nombre = $('nombre-concesionario').value || 'Concesionario';
    concesionario = new Concesionario(nombre);
    $('concesionario-info').textContent = concesionario.info();
  });


  actualizarSelects();
});

window.__uiAcelerar = function(idx) {
  const v = vehiculos[idx]; if (!v) return; v.acelerar(20);
  renderList('vehiculos-list', vehiculos, (v,i) => `${i}: ${v.info()} <button onclick="window.__uiAcelerar(${i})">Acelerar</button> <button onclick="window.__uiFrenar(${i})">Frenar</button> <button onclick="window.__uiAgregar(${i})">Agregar al concesionario</button>`);
};

window.__uiFrenar = function(idx) {
  const v = vehiculos[idx]; if (!v) return; v.frenar();
  renderList('vehiculos-list', vehiculos, (v,i) => `${i}: ${v.info()} <button onclick="window.__uiAcelerar(${i})">Acelerar</button> <button onclick="window.__uiFrenar(${i})">Frenar</button> <button onclick="window.__uiAgregar(${i})">Agregar al concesionario</button>`);
};

window.__uiAgregar = function(idx) {
  if (!concesionario) { alert('Crea primero un concesionario'); return; }
  concesionario.agregarVehiculo(vehiculos[idx]);
  $('concesionario-info').textContent = concesionario.info();
};

