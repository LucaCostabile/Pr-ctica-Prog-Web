const matriz = [
  [1, 2, 3],
  [4, 5, 6],
  [7, 8, 9]
];

const sumaFilas = matriz.map(fila => fila.reduce((acc, n) => acc + n, 0));

const sumaColumnas = matriz[0].map((_, colIdx) =>
  matriz.reduce((acc, fila) => acc + fila[colIdx], 0)
);

document.addEventListener('DOMContentLoaded', () => {
  const tabla = document.getElementById('tabla-matriz');
  tabla.innerHTML = matriz.map(fila => `<tr>${fila.map(n => `<td>${n}</td>`).join('')}</tr>`).join('');

  document.getElementById('suma-filas').innerHTML = `<strong>Suma de filas:</strong> [${sumaFilas.join(', ')}]`;

  document.getElementById('suma-columnas').innerHTML = `<strong>Suma de columnas:</strong> [${sumaColumnas.join(', ')}]`;
});
