<?php
require 'config.php';
$vistaActual = 'alta';
require 'partials/header.php';
?>

  <div class="intake-card">
    <div class="intake-head">
      <span class="intake-tag">FORMULARIO DE INGRESO</span>
      <h2>Nueva ficha clínica</h2>
    </div>

    <?php if (isset($_GET['ok'])): ?>
      <p class="form-msg-block">✔ Paciente registrado correctamente.</p>
    <?php endif; ?>

    <?php if (isset($_GET['error'])): ?>
      <p class="form-msg-block form-msg-error">Completa los campos obligatorios.</p>
    <?php endif; ?>

    <form action="guardar.php" method="POST">
      <div class="field">
        <label for="nombre">Nombre del paciente</label>
        <input type="text" id="nombre" name="nombre" required>
      </div>

      <div class="field-row">
        <div class="field">
          <label for="enfermedad">Enfermedad / diagnóstico</label>
          <input type="text" id="enfermedad" name="enfermedad" required>
        </div>
        <div class="field field-small">
          <label for="es_anomalia">¿Es una anomalía?</label>
          <select id="es_anomalia" name="es_anomalia" required>
            <option value="" disabled selected>Selecciona...</option>
            <option value="si">Sí</option>
            <option value="no">No</option>
          </select>
        </div>
      </div>

      <div class="field">
        <label for="medicina">Medicina necesaria</label>
        <input type="text" id="medicina" name="medicina">
      </div>

      <div class="field">
        <label for="foto">Foto del paciente (URL de imagen)</label>
        <input type="url" id="foto" name="foto" placeholder="https://ejemplo.com/foto.png">
        <span class="field-hint">Opcional. Pega el link de una imagen alojada en internet.</span>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-primary">Registrar paciente</button>
      </div>
    </form>
  </div>

<?php require 'partials/footer.php'; ?>
