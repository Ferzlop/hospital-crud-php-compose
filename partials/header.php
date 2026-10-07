<?php
/**
 * Cabecera compartida por todas las páginas.
 * Espera que $pdo (config.php) y $vistaActual ya existan antes de incluirse.
 */

// Estadísticas para las "píldoras" de arriba (se recalculan en cada página).
// Postgres no deja sumar un booleano directo como MySQL; por eso usamos
// CASE WHEN ... THEN 1 ELSE 0 END para convertirlo primero a número.
$statsStmt = $pdo->query("SELECT COUNT(*) AS total, SUM(CASE WHEN es_anomalia = 'si' THEN 1 ELSE 0 END) AS anomalias FROM pacientes");
$stats = $statsStmt->fetch(PDO::FETCH_ASSOC);
$totalPacientes = $stats['total'] ?? 0;
$totalAnomalias = $stats['anomalias'] ?? 0;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Hospital de Anomalías — Registro de Pacientes</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body>

<div class="hazard-strip"></div>

<header class="topbar">
  <div class="brand">
    <div class="brand-emblem">☣</div>
    <div class="brand-text">
      <h1>Hospital de Anomalías</h1>
    </div>
  </div>
  <div class="stat-pills">
    <div class="pill"><span class="pill-num"><?= $totalPacientes ?></span><span class="pill-lbl">Pacientes</span></div>
    <div class="pill pill-anomaly"><span class="pill-num"><?= $totalAnomalias ?></span><span class="pill-lbl">Anomalías</span></div>
  </div>
</header>

<nav class="tabs">
  <a href="index.php" class="tab-btn <?= $vistaActual === 'alta' ? 'active' : '' ?>">📋 Alta de paciente</a>
  <a href="reporte.php" class="tab-btn <?= $vistaActual === 'reporte' ? 'active' : '' ?>">📑 Reporte general</a>
  <a href="individual.php" class="tab-btn <?= $vistaActual === 'individual' ? 'active' : '' ?>">🔎 Reporte individual</a>
</nav>

<main class="content">
