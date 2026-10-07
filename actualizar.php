<?php
/**
 * — recibe el formulario de editar.php y guarda los cambios.
 */
require 'config.php';

$id = $_POST['id'] ?? null;
$nombre = trim($_POST['nombre'] ?? '');
$enfermedad = trim($_POST['enfermedad'] ?? '');
$esAnomalia = $_POST['es_anomalia'] ?? '';
$medicina = trim($_POST['medicina'] ?? '');
$foto = trim($_POST['foto'] ?? '');

if (!$id || $nombre === '' || $enfermedad === '' || $esAnomalia === '') {
    header("Location: reporte.php");
    exit;
}

if ($medicina === '') {
    $medicina = 'No especificada';
}

$stmt = $pdo->prepare(
    "UPDATE pacientes SET nombre = ?, enfermedad = ?, es_anomalia = ?, medicina = ?, foto = ? WHERE id = ?"
);
$stmt->execute([$nombre, $enfermedad, $esAnomalia, $medicina, $foto, $id]);

header("Location: reporte.php?ok=2");
exit;
