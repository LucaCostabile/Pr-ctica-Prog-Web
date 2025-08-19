const estudiantes = [
  { nombre: "Ana", nota: 8 },
  { nombre: "Luis", nota: 5 },
  { nombre: "Carla", nota: 9 },
  { nombre: "Juan", nota: 6 },
  { nombre: "Pedro", nota: 4 }
];

const aprobados = estudiantes.filter(e => e.nota >= 6).map(e => e.nombre);

estudiantes.sort((a, b) => b.nota - a.nota);

definirPromedio = arr => arr.reduce((acc, e) => acc + e.nota, 0) / arr.length;
const promedio = definirPromedio(estudiantes);

document.addEventListener('DOMContentLoaded', () => {
  const listaAprobados = document.getElementById('lista-aprobados');
  listaAprobados.innerHTML = `<strong>Aprobados:</strong> ${aprobados.join(', ')}`;

  const tabla = document.getElementById('tabla-estudiantes');
  tabla.innerHTML = `<tr><th>Nombre</th><th>Nota</th></tr>` +
    estudiantes.map(e => `<tr><td>${e.nombre}</td><td>${e.nota}</td></tr>`).join('');

  const promedioDiv = document.getElementById('promedio');
  promedioDiv.innerHTML = `<strong>Promedio general:</strong> ${promedio.toFixed(2)}`;
});
