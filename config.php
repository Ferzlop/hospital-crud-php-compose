<?php
/**
 * Conexión a la base de datos (PostgreSQL), usando PDO.
 *
 * Con docker-compose, los dos servicios (hosp-web y hosp-db) quedan en la
 * misma red automáticamente (compose la crea sola)
 */

$host = "hosp-db";
$port = 5432;
$dbname = "hospital_anomalias";
$usuario = "postgres";
$password = "hospital123";

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
    $pdo = new PDO($dsn, $usuario, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error de conexión a la base de datos: " . $e->getMessage());
}
