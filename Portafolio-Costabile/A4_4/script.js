// Arreglo de 20 números inventados
const numeros = [5, 3, 8, 2, 5, 9, 3, 5, 7, 8, 2, 5, 3, 9, 1, 3, 8, 6, 3, 5];

// Mostrar arreglo
document.getElementById("arreglo").textContent = numeros.join(", ");

// Calcular el número más repetido
const frecuencia = {};

numeros.forEach(num => {
  frecuencia[num] = (frecuencia[num] || 0) + 1;
});

let masRepetido = null;
let maxRepeticiones = 0;

for (let num in frecuencia) {
  if (frecuencia[num] > maxRepeticiones) {
    masRepetido = num;
    maxRepeticiones = frecuencia[num];
  }
}

document.getElementById("resultado").textContent = 
  `El número más repetido es ${masRepetido} (aparece ${maxRepeticiones} veces).`;
