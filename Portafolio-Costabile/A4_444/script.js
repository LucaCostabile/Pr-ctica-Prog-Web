const form = document.getElementById('login-form');
const usuarioInput = document.getElementById('usuario');
const contrInput = document.getElementById('contrasena');
const mensaje = document.getElementById('mensaje');

const USUARIO_VALIDO = { usuario: 'luca', contrasena: '1234' };
let intentos = 0;

function mostrarMensaje(texto, tipo) {
  mensaje.textContent = texto;
  mensaje.className = 'resultado ' + (tipo === 'error' ? 'error' : 'ok');
}

form.addEventListener('submit', (e) => {
  e.preventDefault();
  if (form.dataset.bloqueado === 'true') return;

  const u = usuarioInput.value.trim();
  const p = contrInput.value;
  if (!u || !p) {
    mostrarMensaje('Completa ambos campos.', 'error');
    return;
  }

  if (u === USUARIO_VALIDO.usuario && p === USUARIO_VALIDO.contrasena) {
    mostrarMensaje('Login exitoso.', 'ok');
    intentos = 0;
    return;
  }

  intentos += 1;
  mostrarMensaje(`Credenciales inválidas. Intento ${intentos} de 3.`, 'error');

  if (intentos >= 3) {
    form.dataset.bloqueado = 'true';
    mostrarMensaje('Cuenta bloqueada tras 3 intentos fallidos.', 'error');
  }
});
