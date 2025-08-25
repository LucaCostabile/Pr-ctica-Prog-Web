// Matriz tridimensional de ejemplo (3 capas x 2 filas x 3 columnas)
const matriz3D = [
  [ [3, 1, 4], [1, 5, 9] ],
  [ [2, 6, 5], [3, 5, 8] ],
  [ [9, 7, 9], [3, 2, 3] ]
];

// Aplanar la matriz 3D a un arreglo 1D
function aplanar(m3d) {
  // usar reduce y concat para mantener estilo funcional
  return m3d.reduce((acc, plano) => acc.concat(plano.reduce((a, fila) => a.concat(fila), [])), []);
}

// Ordenar numéricamente ascendente
function ordenar(arr) {
  return arr.slice().sort((a, b) => a - b);
}

document.addEventListener('DOMContentLoaded', () => {
  const elMatriz = document.getElementById('matriz-3d');
  const elAplanado = document.getElementById('aplanado');
  const elOrdenado = document.getElementById('ordenado');

  elMatriz.textContent = JSON.stringify(matriz3D, null, 2);

  const flat = aplanar(matriz3D);
  elAplanado.textContent = `Arreglo aplanado: [${flat.join(', ')}]`;

  const sorted = ordenar(flat);
  elOrdenado.textContent = `Arreglo ordenado: [${sorted.join(', ')}]`;
});
