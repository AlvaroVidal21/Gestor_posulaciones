<?php
/**
 * app/postulacion.php
 * Funciones de acceso a datos y lógica de negocio para Postulación.
 *
 * Centraliza todas las operaciones CRUD y el cálculo del estado real
 * (incluyendo la lógica de vencimiento), evitando dispersar consultas SQL
 * en las páginas del frontend.
 *
 * Sobre el estado "Vencido" (ver DEC-003 en docs/05_decisiones.md):
 * No se almacena en BD. Se calcula en cada consulta según la regla:
 *   - Si estado almacenado es "Rechazado" → se muestra "Rechazado"
 *   - Si estado almacenado es "Postulado" y pasaron >15 días desde
 *     fecha_ultima_actualizacion → se muestra "Vencido"
 *   - En cualquier otro caso → se muestra "Postulado"
 */

require_once __DIR__ . '/config.php';

/**
 * Obtiene todas las postulaciones con su estado real calculado.
 *
 * @return array Lista de postulaciones con campo "estado_real" agregado
 */
function obtener_todas(): array {
    $pdo = obtener_conexion();
    $stmt = $pdo->query('SELECT * FROM postulaciones ORDER BY fecha_ultima_actualizacion DESC');
    $postulaciones = $stmt->fetchAll();

    foreach ($postulaciones as &$p) {
        $p['estado_real'] = calcular_estado($p);
    }
    unset($p);

    return $postulaciones;
}

/**
 * Obtiene una postulación por su ID.
 *
 * @param int $id
 * @return array|null Arreglo asociativo o null si no existe
 */
function obtener_por_id(int $id): ?array {
    $pdo = obtener_conexion();
    $stmt = $pdo->prepare('SELECT * FROM postulaciones WHERE id = :id');
    $stmt->execute([':id' => $id]);
    $postulacion = $stmt->fetch();

    if ($postulacion) {
        $postulacion['estado_real'] = calcular_estado($postulacion);
    }

    return $postulacion ?: null;
}

/**
 * Calcula el estado real de una postulación aplicando la regla de vencimiento.
 *
 * Regla de negocio (docs/02_reglas_negocio.md):
 * - Si han pasado más de 15 días desde fecha_ultima_actualizacion
 *   y el estado almacenado es "Postulado", el estado real es "Vencido".
 * - Si el estado almacenado es "Rechazado", se respeta.
 * - Si se actualiza una postulación vencida, vuelve a "Postulado"
 *   (esto se maneja en el formulario de edición, Fase 5).
 *
 * @param array $postulacion Fila de la BD (debe tener 'estado' y 'fecha_ultima_actualizacion')
 * @return string Estado real: "Postulado", "Vencido" o "Rechazado"
 */
function calcular_estado(array $postulacion): string {
    if ($postulacion['estado'] === 'Rechazado') {
        return 'Rechazado';
    }

    if ($postulacion['estado'] === 'Postulado') {
        $fecha_actualizacion = new DateTime($postulacion['fecha_ultima_actualizacion']);
        $hoy = new DateTime();
        $diferencia = $fecha_actualizacion->diff($hoy)->days;

        if ($diferencia > 15) {
            return 'Vencido';
        }
    }

    return 'Postulado';
}

/**
 * Obtiene métricas agregadas para el dashboard.
 *
 * @return array Arreglo con: total, activas, rechazadas, vencidas, por_plataforma
 */
function obtener_metricas(): array {
    $postulaciones = obtener_todas();

    $total = count($postulaciones);
    $activas = 0;
    $rechazadas = 0;
    $vencidas = 0;
    $por_plataforma = [];

    foreach ($postulaciones as $p) {
        switch ($p['estado_real']) {
            case 'Postulado':
                $activas++;
                break;
            case 'Rechazado':
                $rechazadas++;
                break;
            case 'Vencido':
                $vencidas++;
                break;
        }

        $plataforma = $p['plataforma'];
        if (!isset($por_plataforma[$plataforma])) {
            $por_plataforma[$plataforma] = 0;
        }
        $por_plataforma[$plataforma]++;
    }

    // Ordenar plataformas de mayor a menor cantidad
    arsort($por_plataforma);

    return [
        'total' => $total,
        'activas' => $activas,
        'rechazadas' => $rechazadas,
        'vencidas' => $vencidas,
        'por_plataforma' => $por_plataforma,
    ];
}

/**
 * Busca y filtra postulaciones según criterios especificados.
 *
 * CU-003: Búsqueda textual sobre empresa, puesto y plataforma.
 * CU-004: Filtros combinables por estado, empresa y plataforma.
 *
 * El filtrado se hace en PHP porque "Vencido" es un estado calculado,
 * no almacenado en BD, por lo que no se puede filtrar directamente en SQL.
 *
 * @param array $criterios Arreglo con claves opcionales: busqueda, estado, empresa, plataforma
 * @return array Lista de postulaciones filtradas
 */
function buscar_postulaciones(array $criterios = []): array {
    $postulaciones = obtener_todas();

    // Filtrar por texto de búsqueda (coincidencia parcial en empresa, puesto o plataforma)
    if (!empty($criterios['busqueda'])) {
        $termino = strtolower($criterios['busqueda']);
        $postulaciones = array_filter($postulaciones, function ($p) use ($termino) {
            return strpos(strtolower($p['empresa']), $termino) !== false
                || strpos(strtolower($p['puesto']), $termino) !== false
                || strpos(strtolower($p['plataforma']), $termino) !== false;
        });
    }

    // Filtrar por estado (usa estado_real, no el almacenado)
    if (!empty($criterios['estado'])) {
        $postulaciones = array_filter($postulaciones, function ($p) use ($criterios) {
            return $p['estado_real'] === $criterios['estado'];
        });
    }

    // Filtrar por empresa (coincidencia exacta parcial)
    if (!empty($criterios['empresa'])) {
        $termino = strtolower($criterios['empresa']);
        $postulaciones = array_filter($postulaciones, function ($p) use ($termino) {
            return strpos(strtolower($p['empresa']), $termino) !== false;
        });
    }

    // Filtrar por plataforma (coincidencia exacta)
    if (!empty($criterios['plataforma'])) {
        $postulaciones = array_filter($postulaciones, function ($p) use ($criterios) {
            return $p['plataforma'] === $criterios['plataforma'];
        });
    }

    // Re-indexar array después de los filtros
    return array_values($postulaciones);
}

/**
 * Actualiza los datos de una postulación existente.
 *
 * Regla 6 (docs/02_reglas_negocio.md):
 * Si la postulación estaba "Vencido" y se actualiza con estado "Postulado",
 * vuelve a estado activo porque se reinicia el contador de 15 días
 * al modificar fecha_ultima_actualizacion.
 *
 * @param int   $id    ID de la postulación
 * @param array $datos Arreglo con campos a actualizar
 * @return bool True si se actualizó correctamente
 */
function actualizar_postulacion(int $id, array $datos): bool {
    $pdo = obtener_conexion();

    $stmt = $pdo->prepare('
        UPDATE postulaciones SET
            empresa = :empresa,
            puesto = :puesto,
            plataforma = :plataforma,
            url_oferta = :url_oferta,
            fecha_postulacion = :fecha_postulacion,
            fecha_ultima_actualizacion = :fecha_ultima_actualizacion,
            estado = :estado,
            notas = :notas
        WHERE id = :id
    ');

    return $stmt->execute([
        ':id' => $id,
        ':empresa' => $datos['empresa'],
        ':puesto' => $datos['puesto'],
        ':plataforma' => $datos['plataforma'],
        ':url_oferta' => $datos['url_oferta'] ?: null,
        ':fecha_postulacion' => $datos['fecha_postulacion'],
        ':fecha_ultima_actualizacion' => date('Y-m-d'), // Siempre se actualiza a hoy
        ':estado' => $datos['estado'],
        ':notas' => $datos['notas'] ?: null,
    ]);
}

/**
 * Elimina una postulación por su ID.
 *
 * @param int $id ID de la postulación
 * @return bool True si se eliminó correctamente
 */
function eliminar_postulacion(int $id): bool {
    $pdo = obtener_conexion();
    $stmt = $pdo->prepare('DELETE FROM postulaciones WHERE id = :id');
    return $stmt->execute([':id' => $id]);
}

/**
 * Obtiene la lista de plataformas únicas registradas.
 *
 * @return array Lista de nombres de plataforma
 */
function obtener_plataformas(): array {
    $pdo = obtener_conexion();
    $stmt = $pdo->query('SELECT DISTINCT plataforma FROM postulaciones ORDER BY plataforma');
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}
