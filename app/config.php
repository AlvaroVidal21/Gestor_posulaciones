<?php
/**
 * app/config.php
 * Configuración general del sistema.
 *
 * Define la ruta de la base de datos SQLite y proporciona la función
 * obtener_conexion() usada por todos los módulos para interactuar con la BD.
 *
 * Decisión técnica:
 * SQLite no requiere servidor ni credenciales, solo una ruta al archivo .sqlite.
 * Esto simplifica el despliegue (ver DEC-001 en docs/05_decisiones.md).
 */

// Ruta absoluta al archivo de base de datos SQLite
define('DB_PATH', __DIR__ . '/../database/gestor_postulaciones.sqlite');

/**
 * Obtiene una conexión PDO a la base de datos SQLite.
 *
 * @return PDO Objeto de conexión configurado con:
 *   - Modo excepción: facilita depuración
 *   - Modo fetch asociativo: devuelve resultados como arrays con nombre de columna
 *   - PRAGMA foreign_keys: garantiza integridad referencial (a futuro si se agregan relaciones)
 */
function obtener_conexion(): PDO {
    $pdo = new PDO('sqlite:' . DB_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $pdo->exec('PRAGMA foreign_keys = ON');
    return $pdo;
}
