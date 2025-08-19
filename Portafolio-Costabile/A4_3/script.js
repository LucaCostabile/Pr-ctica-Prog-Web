// Primer ejercicio
const numeros = [3, 6, 12, 15, 18];
const cubosPares = numeros
  .filter(num => num % 2 === 0)     // Filtrar pares
  .map(num => num ** 3);            // Elevar al cubo

document.getElementById("cubos").textContent = cubosPares.join(", ");

// Segundo ejercicio
const empleados = [
  { nombre: "Ana", departamento: "ventas" },
  { nombre: "Luis", departamento: "IT" },
  { nombre: "Marta", departamento: "ventas" },
  { nombre: "Pedro", departamento: "IT" },
  { nombre: "Sofía", departamento: "Marketing" }
];

// Agrupar por departamento
const agrupados = empleados.reduce((acc, empleado) => {
  if (!acc[empleado.departamento]) {
    acc[empleado.departamento] = [];
  }
  acc[empleado.departamento].push(empleado.nombre);
  return acc;
}, {});

// Mostrar el resultado en el HTML
document.getElementById("empleados").textContent = JSON.stringify(agrupados, null, 2);
