document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("form-trabajo");

  form.addEventListener("submit", (e) => {
    e.preventDefault();
    alert("Solicitud enviada correctamente. ¡Gracias por contactarme!");
    form.reset();
  });
});
