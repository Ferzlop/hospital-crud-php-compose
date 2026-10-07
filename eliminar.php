<?php
/**
 * DELETE — borra un paciente por su id.
 */
require 'config.php';

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $pdo->prepare("DELETE FROM pacientes WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: reporte.php?ok=3");
exit;
