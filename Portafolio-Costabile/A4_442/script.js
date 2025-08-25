// Arreglo de ejemplo
const usuarios = [
  { nombre: 'ana', clave: 'abc123' },
  { nombre: 'juan pérez', clave: 'secret' },
  { nombre: 'maria', clave: 'qwerty' }
];

// Función para capitalizar cada palabra del nombre
function capitalizarNombre(nombre) {
  return nombre.split(' ').map(p => p.charAt(0).toUpperCase() + p.slice(1)).join(' ');
}

// Funcion simple para "encriptar" clave: desplazar chars por 3 (Caesar simple)
function encriptarClave(clave) {
  return clave.split('').map(ch => {
    const code = ch.charCodeAt(0);
    // Sólo transformar letras y dígitos de forma sencilla
    if (code >= 48 && code <= 57) { // 0-9
      return String.fromCharCode(((code - 48 + 3) % 10) + 48);
    }
    if (code >= 65 && code <= 90) { // A-Z
      return String.fromCharCode(((code - 65 + 3) % 26) + 65);
    }
    if (code >= 97 && code <= 122) { // a-z
      return String.fromCharCode(((code - 97 + 3) % 26) + 97);
    }
    return ch;
  }).join('');
}

// Aplicar funciones de orden superior con map
function procesarUsuarios(arr) {
  return arr.map(u => ({
    nombre: capitalizarNombre(u.nombre),
    clave: encriptarClave(u.clave)
  }));
}

document.addEventListener('DOMContentLoaded', () => {
  document.getElementById('original').textContent = JSON.stringify(usuarios, null, 2);
  const procesados = procesarUsuarios(usuarios);
  document.getElementById('resultado').textContent = JSON.stringify(procesados, null, 2);
});
