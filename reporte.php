<?php
require 'config.php';
$vistaActual = 'reporte';

$busqueda = trim($_GET['buscar'] ?? '');

if ($busqueda !== '') {
    $like = '%' . $busqueda . '%';
    // ILIKE es como LIKE pero sin distinguir mayúsculas/minúsculas
    $stmt = $pdo->prepare(
        "SELECT * FROM pacientes WHERE nombre ILIKE ? OR enfermedad ILIKE ? ORDER BY id DESC"
    );
    $stmt->execute([$like, $like]);
} else {
    $stmt = $pdo->query("SELECT * FROM pacientes ORDER BY id DESC");
}
$pacientes = $stmt->fetchAll(PDO::FETCH_ASSOC);

require 'partials/header.php';
?>

  <div class="reporte-head">
    <div>
      <span class="intake-tag">EXPEDIENTES</span>
      <h2>Reporte general de pacientes</h2>
    </div>
    <form method="GET" action="reporte.php">
      <input type="text" name="buscar" placeholder="Buscar por nombre o enfermedad..." value="<?= htmlspecialchars($busqueda) ?>">
    </form>
  </div>

  <?php if (isset($_GET['ok'])): ?>
    <p class="form-msg-block">
      <?php
        $mensajes = [1 => '✔ Paciente registrado.', 2 => '✔ Paciente actualizado.', 3 => '✔ Paciente eliminado.'];
        echo $mensajes[$_GET['ok']] ?? '';
      ?>
    </p>
  <?php endif; ?>

  <?php if (count($pacientes) === 0): ?>
    <div class="empty-state">
      <div class="empty-icon">🗂️</div>
      <p><?= $busqueda !== '' ? 'No se encontraron pacientes con ese criterio.' : 'No hay pacientes registrados todavía.' ?></p>
      <p class="empty-sub">Ve a "Alta de paciente" para registrar el primero.</p>
    </div>
  <?php else: ?>
    <div class="grid-pacientes">
      <?php foreach ($pacientes as $p): ?>
        <?php $esAnomalia = $p['es_anomalia'] === 'si'; ?>
        <div class="ficha">
          <div class="ficha-photo">
            <?php if (!empty($p['foto'])): ?>
              <img src="<?= htmlspecialchars($p['foto']) ?>" alt="Foto de <?= htmlspecialchars($p['nombre']) ?>"
                   onerror="this.parentElement.innerHTML='🧑‍⚕️'">
            <?php else: ?>
              🧑‍⚕️
            <?php endif; ?>
          </div>
          <span class="ficha-badge <?= $esAnomalia ? 'badge-anomaly' : 'badge-normal' ?>">
            <?= $esAnomalia ? '⚠ Anomalía' : '✓ Sin anomalía' ?>
          </span>
          <div class="ficha-body">
            <div class="ficha-nombre"><?= htmlspecialchars($p['nombre']) ?></div>
            <div class="ficha-row"><strong>Diagnóstico:</strong> <?= htmlspecialchars($p['enfermedad']) ?></div>
            <div class="ficha-row"><strong>Medicina:</strong> <?= htmlspecialchars($p['medicina']) ?></div>
            <div class="ficha-actions">
              <a href="individual.php?id=<?= $p['id'] ?>" class="btn-chip">Ver ficha</a>
              <a href="editar.php?id=<?= $p['id'] ?>" class="btn-chip btn-chip-edit">Editar</a>
              <a href="eliminar.php?id=<?= $p['id'] ?>" class="btn-chip btn-chip-delete"
                 onclick="return confirm('¿Seguro que quieres eliminar a <?= htmlspecialchars($p['nombre']) ?>?');">Eliminar</a>
            </div>
            <div class="ficha-id">EXP-<?= $p['id'] ?> · registrado <?= $p['fecha_registro'] ?></div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  <?php endif; ?>

<?php require 'partials/footer.php'; ?>
