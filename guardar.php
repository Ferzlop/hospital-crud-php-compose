<?php
/**
 * CREATE — recibe el formulario de index.php y lo inserta en la base de datos.
 * No muestra ninguna página propia: solo procesa y redirige.
 */
require 'config.php';

$nombre = trim($_POST['nombre'] ?? '');
$enfermedad = trim($_POST['enfermedad'] ?? '');
$esAnomalia = $_POST['es_anomalia'] ?? '';
$medicina = trim($_POST['medicina'] ?? '');
$foto = trim($_POST['foto'] ?? '');

// Validación mínima de los campos obligatorios.
if ($nombre === '' || $enfermedad === '' || $esAnomalia === '') {
    header("Location: index.php?error=1");
    exit;
}

if ($medicina === '') {
    $medicina = 'No especificada';
}

// Usamos una consulta preparada (con "?") en vez de pegar los valores
// directo en el texto del SQL, para evitar inyección SQL.
$stmt = $pdo->prepare(
    "INSERT INTO pacientes (nombre, enfermedad, es_anomalia, medicina, foto) VALUES (?, ?, ?, ?, ?)"
);
$stmt->execute([$nombre, $enfermedad, $esAnomalia, $medicina, $foto]);

header("Location: index.php?ok=1");
exit;
