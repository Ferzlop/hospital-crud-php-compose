<?php
require 'config.php';
$vistaActual = 'individual';

$idSeleccionado = $_GET['id'] ?? null;
$paciente = null;

if ($idSeleccionado) {
    $stmt = $pdo->prepare("SELECT * FROM pacientes WHERE id = ?");
    $stmt->execute([$idSeleccionado]);
    $paciente = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
}

// Lista de todos los pacientes, solo id + nombre, para llenar el <select>.
$todos = $pdo->query("SELECT id, nombre FROM pacientes ORDER BY nombre ASC")->fetchAll(PDO::FETCH_ASSOC);

require 'partials/header.php';
?>

  <div class="intake-card intake-card-wide">
    <div class="intake-head">
      <span class="intake-tag">EXPEDIENTE ÚNICO</span>
      <h2>Reporte individual de paciente</h2>
    </div>

    <form method="GET" action="individual.php" class="select-form">
      <label for="id">Selecciona un paciente</label>
      <select id="id" name="id" onchange="this.form.submit()">
        <option value="">-- Elige un paciente --</option>
        <?php foreach ($todos as $row): ?>
          <option value="<?= $row['id'] ?>" <?= ($idSeleccionado == $row['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($row['nombre']) ?> (EXP-<?= $row['id'] ?>)
          </option>
        <?php endforeach; ?>
      </select>
    </form>

    <?php if ($idSeleccionado && !$paciente): ?>
      <p class="form-msg-block form-msg-error">No se encontró ningún paciente con ese folio.</p>
    <?php endif; ?>

    <?php if ($paciente): ?>
      <?php $esAnomalia = $paciente['es_anomalia'] === 'si'; ?>
      <div class="ficha-detalle">
        <div class="ficha-detalle-photo">
          <?php if (!empty($paciente['foto'])): ?>
            <img src="<?= htmlspecialchars($paciente['foto']) ?>" alt="Foto de <?= htmlspecialchars($paciente['nombre']) ?>"
                 onerror="this.parentElement.innerHTML='🧑‍⚕️'">
          <?php else: ?>
            🧑‍⚕️
          <?php endif; ?>
        </div>
        <div class="ficha-detalle-body">
          <span class="ficha-badge <?= $esAnomalia ? 'badge-anomaly' : 'badge-normal' ?>">
            <?= $esAnomalia ? '⚠ Anomalía confirmada' : '✓ Sin anomalía' ?>
          </span>
          <h3><?= htmlspecialchars($paciente['nombre']) ?></h3>
          <div class="detalle-row"><strong>Folio:</strong> EXP-<?= $paciente['id'] ?></div>
          <div class="detalle-row"><strong>Diagnóstico:</strong> <?= htmlspecialchars($paciente['enfermedad']) ?></div>
          <div class="detalle-row"><strong>Medicina necesaria:</strong> <?= htmlspecialchars($paciente['medicina']) ?></div>
          <div class="detalle-row"><strong>Fecha de registro:</strong> <?= $paciente['fecha_registro'] ?></div>

          <div class="ficha-actions" style="margin-top:18px;">
            <a href="editar.php?id=<?= $paciente['id'] ?>" class="btn-chip btn-chip-edit">Editar</a>
            <a href="eliminar.php?id=<?= $paciente['id'] ?>" class="btn-chip btn-chip-delete"
               onclick="return confirm('¿Seguro que quieres eliminar a <?= htmlspecialchars($paciente['nombre']) ?>?');">Eliminar</a>
          </div>
        </div>
      </div>
    <?php endif; ?>
  </div>

<?php require 'partials/footer.php'; ?>
