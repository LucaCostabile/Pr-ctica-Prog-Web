// Objeto Libro (constructor funcional)
function Libro(titulo, autor, anio) {
  this.titulo = titulo;
  this.autor = autor;
  this.anio = anio;
}

Libro.prototype.descripcion = function() {
  return `${this.titulo} — ${this.autor} (${this.anio})`;
};

// Objeto cuentaBancaria
function CuentaBancaria(titular, saldoInicial = 0) {
  this.titular = titular;
  this.saldo = saldoInicial;
}

CuentaBancaria.prototype.depositar = function(monto) {
  if (monto <= 0) return false;
  this.saldo += monto;
  return true;
};

CuentaBancaria.prototype.retirar = function(monto) {
  if (monto <= 0 || monto > this.saldo) return false;
  this.saldo -= monto;
  return true;
};

// Objeto Rectangulo
function Rectangulo(ancho, alto) {
  this.ancho = ancho;
  this.alto = alto;
}

Rectangulo.prototype.area = function() {
  return this.ancho * this.alto;
};

Rectangulo.prototype.perimetro = function() {
  return 2 * (this.ancho + this.alto);
};

document.addEventListener('DOMContentLoaded', () => {
  const libro = new Libro('Cien años de soledad', 'Gabriel García Márquez', 1967);
  document.getElementById('libro').textContent = libro.descripcion();

  const cuenta = new CuentaBancaria('Luca Costabile', 1000);
  cuenta.depositar(250);
  cuenta.retirar(100);
  document.getElementById('cuenta').textContent = `Titular: ${cuenta.titular} — Saldo: $${cuenta.saldo}`;

  const rect = new Rectangulo(5, 3);
  document.getElementById('rectangulo').textContent = `Ancho: ${rect.ancho}, Alto: ${rect.alto} — Área: ${rect.area()}, Perímetro: ${rect.perimetro()}`;
});
