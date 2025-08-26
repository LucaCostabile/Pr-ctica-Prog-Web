const lista = document.getElementById('lista-tareas');
const input = document.getElementById('nueva-tarea');
const btnAgregar = document.getElementById('btn-agregar');
const registro = document.getElementById('registro');

function crearItem(texto) {
  const li = document.createElement('li');
  const span = document.createElement('span');
  span.className = 'texto';
  span.textContent = texto;

  const acciones = document.createElement('div');
  acciones.className = 'acciones';

  const btnCheck = document.createElement('button');
  btnCheck.textContent = '✔';
  btnCheck.title = 'Marcar como completada';
  btnCheck.addEventListener('click', () => {
    li.classList.toggle('completada');
    // Cambiar atributo data-completa para observar cambios en atributos
    li.setAttribute('data-completa', li.classList.contains('completada') ? 'true' : 'false');
  });

  const btnBorrar = document.createElement('button');
  btnBorrar.textContent = '✖';
  btnBorrar.title = 'Eliminar';
  btnBorrar.addEventListener('click', () => lista.removeChild(li));

  acciones.appendChild(btnCheck);
  acciones.appendChild(btnBorrar);

  li.appendChild(span);
  li.appendChild(acciones);
  return li;
}

btnAgregar.addEventListener('click', () => {
  const val = input.value.trim();
  if (!val) return;
  const item = crearItem(val);
  lista.appendChild(item);
  input.value = '';
});

// MutationObserver para registrar cambios en el DOM
const observer = new MutationObserver((mutationsList) => {
  mutationsList.forEach(m => {
    if (m.type === 'childList') {
      const targetName = m.target.nodeName;
      registro.textContent = `Cambio: childList en ${targetName}`;
    }
    if (m.type === 'attributes') {
      registro.textContent = `Cambio: attributes en ${m.target.nodeName}`;
    }
  });
});

observer.observe(lista, { childList: true, subtree: true, attributes: true });
