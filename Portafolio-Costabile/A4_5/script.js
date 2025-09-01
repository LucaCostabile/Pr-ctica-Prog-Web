
class Motor {
  constructor(tipo, caballos, descripcion) {
    this.tipo = tipo;
    this.caballos = Number(caballos) || 0;
    this.descripcion = descripcion || '';
  }

  info() {
    return `${this.tipo} - ${this.caballos} CV - ${this.descripcion}`;
  }
}

class Vehiculo {
  constructor(marca, modelo, anio, motor = null) {
    this.marca = marca;
    this.modelo = modelo;
    this.anio = anio;
    this.motor = motor;
    this.velocidad = 0; 
  }

  acelerar(delta = 10) {
    this.velocidad += delta;
    return this.velocidad;
  }

  frenar() {
    this.velocidad = 0;
    return this.velocidad;
  }

  info() {
    return `${this.marca} ${this.modelo} (${this.anio}) - Vel: ${this.velocidad} km/h - Motor: ${this.motor ? this.motor.info() : 'sin motor'}`;
  }

  compararVelocidad(otro) {
    return this.velocidad - (otro?.velocidad || 0);
  }
}

class Auto extends Vehiculo {
  constructor(marca, modelo, anio, motor, cantPuertas = 4) {
    super(marca, modelo, anio, motor);
    this.cantPuertas = Number(cantPuertas) || 4;
  }

  info() {
    return `Auto: ${super.info()} - Puertas: ${this.cantPuertas}`;
  }
}

class Camion extends Vehiculo {
  constructor(marca, modelo, anio, motor, capacidadCarga = 0, frigorifico = false) {
    super(marca, modelo, anio, motor);
    this.capacidadCarga = Number(capacidadCarga) || 0;
    this.frigorifico = !!frigorifico;
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

  info() {
    return `Camión: ${super.info()} - Capacidad: ${this.capacidadCarga} kg - Carga actual: ${this.cargaActual} kg - Frigorífico: ${this.frigorifico}`;
  }
}

class Concesionario {
  constructor(nombre) {
    this.nombre = nombre;
    this.vehiculos = [];
  }

  agregarVehiculo(v) {
    this.vehiculos.push(v);
  }

  info() {
    return `${this.nombre} - Vehículos: ${this.vehiculos.length}`;
  }
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
const vehiculos = [];
let concesionario = null;

function $(id) { return document.getElementById(id); }

function actualizarSelects() {
  const motorSelects = [$('auto-motor'), $('camion-motor')];
  motorSelects.forEach(sel => {
    if (!sel) return;
    sel.innerHTML = '<option value="">-- seleccionar motor --</option>' + motores.map((m, i) => `<option value="${i}">${m.info()}</option>`).join('');
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

  $('crear-auto').addEventListener('click', () => {
    const motorIdx = $('auto-motor').value;
    const motor = motorIdx !== '' ? motores[Number(motorIdx)] : null;
    const a = new Auto($('auto-marca').value, $('auto-modelo').value, $('auto-anio').value, motor, $('auto-puertas').value);
    vehiculos.push(a);
    renderList('vehiculos-list', vehiculos, (v,i) => `${i}: ${v.info()} <button onclick="window.__uiAcelerar(${i})">Acelerar</button> <button onclick="window.__uiFrenar(${i})">Frenar</button> <button onclick="window.__uiAgregar(${i})">Agregar al concesionario</button>`);
  });

  $('crear-camion').addEventListener('click', () => {
    const motorIdx = $('camion-motor').value;
    const motor = motorIdx !== '' ? motores[Number(motorIdx)] : null;
    const c = new Camion($('camion-marca').value, $('camion-modelo').value, $('camion-anio').value, motor, $('camion-capacidad').value, $('camion-frigorifico').checked);
    vehiculos.push(c);
    renderList('vehiculos-list', vehiculos, (v,i) => `${i}: ${v.info()} <button onclick="window.__uiAcelerar(${i})">Acelerar</button> <button onclick="window.__uiFrenar(${i})">Frenar</button> <button onclick="window.__uiAgregar(${i})">Agregar al concesionario</button>`);
  });

  $('crear-concesionario').addEventListener('click', () => {
    const nombre = $('nombre-concesionario').value || 'Concesionario';
    concesionario = new Concesionario(nombre);
    $('concesionario-info').textContent = concesionario.info();
  });
});


window.__uiAcelerar = function(idx) {
  const v = vehiculos[idx];
  if (!v) return;
  v.acelerar(20);
  renderList('vehiculos-list', vehiculos, (v,i) => `${i}: ${v.info()} <button onclick="window.__uiAcelerar(${i})">Acelerar</button> <button onclick="window.__uiFrenar(${i})">Frenar</button> <button onclick="window.__uiAgregar(${i})">Agregar al concesionario</button>`);
};

window.__uiFrenar = function(idx) {
  const v = vehiculos[idx];
  if (!v) return;
  v.frenar();
  renderList('vehiculos-list', vehiculos, (v,i) => `${i}: ${v.info()} <button onclick="window.__uiAcelerar(${i})">Acelerar</button> <button onclick="window.__uiFrenar(${i})">Frenar</button> <button onclick="window.__uiAgregar(${i})">Agregar al concesionario</button>`);
};

window.__uiAgregar = function(idx) {
  if (!concesionario) { alert('Crea primero un concesionario'); return; }
  concesionario.agregarVehiculo(vehiculos[idx]);
  $('concesionario-info').textContent = concesionario.info();
};
