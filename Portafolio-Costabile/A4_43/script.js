const matriz = [
  [2, 7, 3],
  [4, 5, 6],
  [9, 8, 1]
];

function obtenerDiagonalPrincipal(m) {
  return m.map((fila, i) => fila[i]);
}

function obtenerDiagonalSecundaria(m) {
  return m.map((fila, i) => fila[m.length - 1 - i]);
}

function sumaArray(arr) {
  return arr.reduce((acc, n) => acc + n, 0);
}

const diagonalPrincipal = obtenerDiagonalPrincipal(matriz);
const diagonalSecundaria = obtenerDiagonalSecundaria(matriz);
const sumaDiagonales = sumaArray(diagonalPrincipal) + sumaArray(diagonalSecundaria);

document.addEventListener('DOMContentLoaded', () => {
  const tabla = document.getElementById('tabla-matriz');
  tabla.innerHTML = matriz.map(fila => `<tr>${fila.map(n => `<td>${n}</td>`).join('')}</tr>`).join('');

  document.getElementById('diagonal-principal').innerHTML = `<strong>Diagonal principal:</strong> [${diagonalPrincipal.join(', ')}]`;

  document.getElementById('diagonal-secundaria').innerHTML = `<strong>Diagonal secundaria:</strong> [${diagonalSecundaria.join(', ')}]`;

  document.getElementById('suma-diagonales').innerHTML = `<strong>Suma de ambas diagonales:</strong> ${sumaDiagonales}`;
});
