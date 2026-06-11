<?php
/**
 * database/init.php
 * Script de inicialización de la base de datos.
 *
 * Ejecución: php database/init.php
 * (se ejecuta una sola vez para crear la estructura inicial)
 *
 * Lee database/schema.sql y ejecuta las sentencias CREATE TABLE
 * usando la conexión definida en app/config.php.
 *
 * Por qué existe este script:
 * SQLite crea el archivo .sqlite automáticamente al abrir una conexión
 * con PDO, pero las tablas deben crearse explícitamente. Este script
 * centraliza ese paso inicial.
 */

require_once __DIR__ . '/../app/config.php';

try {
    $pdo = obtener_conexion();
    $schema = file_get_contents(__DIR__ . '/schema.sql');

    if ($schema === false) {
        throw new RuntimeException('No se pudo leer el archivo schema.sql');
    }

    $pdo->exec($schema);

    echo "Base de datos inicializada correctamente.\n";
    echo "Archivo: " . DB_PATH . "\n";
} catch (Exception $e) {
    echo "Error al inicializar la base de datos: " . $e->getMessage() . "\n";
    exit(1);
}
