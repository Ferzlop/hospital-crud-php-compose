<?php
require 'config.php';
$vistaActual = 'reporte'; // resalta "Reporte general", de donde normalmente se llega a editar

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: reporte.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM pacientes WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$p) {
    die("Paciente no encontrado.");
}

require 'partials/header.php';
?>

  <div class="intake-card">
    <div class="intake-head">
      <span class="intake-tag">EDITANDO EXP-<?= $p['id'] ?></span>
      <h2>Actualizar ficha clínica</h2>
    </div>

    <form action="actualizar.php" method="POST">
      <input type="hidden" name="id" value="<?= $p['id'] ?>">

      <div class="field">
        <label for="nombre">Nombre del paciente</label>
        <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($p['nombre']) ?>" required>
      </div>

      <div class="field-row">
        <div class="field">
          <label for="enfermedad">Enfermedad / diagnóstico</label>
          <input type="text" id="enfermedad" name="enfermedad" value="<?= htmlspecialchars($p['enfermedad']) ?>" required>
        </div>
        <div class="field field-small">
          <label for="es_anomalia">¿Es una anomalía?</label>
          <select id="es_anomalia" name="es_anomalia" required>
            <option value="si" <?= $p['es_anomalia'] === 'si' ? 'selected' : '' ?>>Sí</option>
            <option value="no" <?= $p['es_anomalia'] === 'no' ? 'selected' : '' ?>>No</option>
          </select>
        </div>
      </div>

      <div class="field">
        <label for="medicina">Medicina necesaria</label>
        <input type="text" id="medicina" name="medicina" value="<?= htmlspecialchars($p['medicina']) ?>">
      </div>

      <div class="field">
        <label for="foto">Foto del paciente (URL de imagen)</label>
        <input type="url" id="foto" name="foto" value="<?= htmlspecialchars($p['foto']) ?>">
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-primary">Guardar cambios</button>
        <a href="reporte.php" class="btn-cancel">Cancelar</a>
      </div>
    </form>
  </div>

<?php require 'partials/footer.php'; ?>
