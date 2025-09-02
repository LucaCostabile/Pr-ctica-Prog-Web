document.addEventListener('DOMContentLoaded', () => {
  const display = document.getElementById('contador-display');
  const incBtn = document.getElementById('incrementar');
  const decBtn = document.getElementById('decrementar');

  let contador = 0;
  function render(){ display.textContent = `Contador: ${contador}`; }

  incBtn.addEventListener('click', () => { contador += 1; render(); });
  decBtn.addEventListener('click', () => { contador -= 1; render(); });

  // render inicial
  render();
});
