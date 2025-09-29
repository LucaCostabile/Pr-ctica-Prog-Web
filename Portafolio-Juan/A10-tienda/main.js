function actualizarVista(data) {
  const lista = document.getElementById('lista');
  const badge = document.getElementById('badge');
  const total = document.getElementById('total');

  badge.textContent = `Carrito (${data.items} items)`;

  lista.innerHTML = '';
  data.detalle.forEach(item => {
    const li = document.createElement('li');
    li.textContent = `${item.nombre} - ${item.cantidad}`;
    lista.appendChild(li);
  });

  total.textContent = `Total: $${data.total}`;
}

function llamarCarrito(params) {
  const xhr = new XMLHttpRequest();
  xhr.open('GET', 'carrito.php' + (params ? '?' + params : ''), true);
  xhr.onload = function () {
    if (xhr.status === 200) {
      const data = JSON.parse(xhr.responseText);
      actualizarVista(data);
    }
  };
  xhr.send();
}

// Inicializar eventos
window.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.btn-add').forEach(btn => {
    btn.addEventListener('click', () => {
      const id = btn.getAttribute('data-id');
      llamarCarrito(`action=add&id=${encodeURIComponent(id)}`);
    });
  });

  document.getElementById('btnVaciar').addEventListener('click', () => {
    llamarCarrito('action=clear');
  });

  // Cargar estado inicial
  llamarCarrito('');
});
