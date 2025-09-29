<?php
require __DIR__ . '/db.php';
$stmt = $pdo->query("SELECT id, nombre FROM especialidades ORDER BY nombre");
$especialidades = $stmt->fetchAll();
?>
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <title>Clínica - Especialidades</title>
</head>
<body>
  <h1>Especialidades</h1>

  <label for="combo">Seleccione una especialidad:</label>
  <select id="combo">
    <option value="">-- Seleccione --</option>
    <?php foreach ($especialidades as $esp): ?>
      <option value="<?= (int)$esp['id'] ?>"><?= htmlspecialchars($esp['nombre'], ENT_QUOTES, 'UTF-8') ?></option>
    <?php endforeach; ?>
  </select>

  <h2>Descripción</h2>
  <div id="descripcion">Seleccione una especialidad para ver su descripción.</div>

  <script>
    const combo = document.getElementById('combo');
    const descripcion = document.getElementById('descripcion');

    combo.addEventListener('change', async () => {
      const id = combo.value;
      if (!id) {
        descripcion.textContent = 'Seleccione una especialidad para ver su descripción.';
        return;
      }
      try {
        const resp = await fetch('especialidad.php?id=' + encodeURIComponent(id), { cache: 'no-store' });
        if (!resp.ok) {
          descripcion.textContent = 'No se encontró la especialidad.';
          return;
        }
        const data = await resp.json();
        descripcion.textContent = data.descripcion ?? 'Sin descripción.';
      } catch (e) {
        descripcion.textContent = 'Error consultando al servidor.';
      }
    });
  </script>
</body>
</html>
