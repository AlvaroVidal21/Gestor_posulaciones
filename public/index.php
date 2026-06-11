<?php
/**
 * public/index.php
 * Dashboard principal del Gestor de Postulaciones.
 *
 * CU-002: Visualizar Dashboard
 * CU-003: Buscar postulaciones
 * CU-004: Filtrar postulaciones
 *
 * (docs/04_casos_uso.md)
 *
 * Soporta peticiones AJAX para búsqueda sin recargar la página:
 *   ?ajax=1&busqueda=...&estado=...&plataforma=...
 *   Devuelve solo el HTML de la tabla, sin el resto de la página.
 *
 * ¿Por qué AJAX?
 * Al escribir en el buscador, antes la página se recargaba completa,
 * lo que hacía perder el foco del input. Con fetch() solo se actualiza
 * el contenedor #resultados, manteniendo el cursor donde el usuario
 * está escribiendo.
 */

require_once __DIR__ . '/../app/postulacion.php';

$criterios = [
    'busqueda' => $_GET['busqueda'] ?? '',
    'estado' => $_GET['estado'] ?? '',
    'empresa' => $_GET['empresa'] ?? '',
    'plataforma' => $_GET['plataforma'] ?? '',
];

$metricas = obtener_metricas();
$postulaciones = buscar_postulaciones($criterios);
$plataformas = array_keys($metricas['por_plataforma']);

// ─── Modo AJAX ─────────────────────────────────────────────────────────
// Si la petición incluye ?ajax=1, significa que vino de JavaScript (fetch).
// En ese caso solo devolvemos el HTML de la tabla de resultados,
// sin _header.php, _footer.php, métricas, ni formulario.
// Esto permite que el JS reemplace solo el contenido del <div id="resultados">
// sin perder el foco del input.
if (!empty($_GET['ajax'])) {
    resultados_html($postulaciones, $criterios);
    return; // ← importante: detener la ejecución aquí, no renderizar el resto
}

// ─── Modo normal: página completa ──────────────────────────────────────
$mensaje = $_GET['mensaje'] ?? null;
$tipo_mensaje = $_GET['tipo'] ?? 'success';

$titulo = 'Dashboard';
require_once __DIR__ . '/_header.php';
?>

<?php if ($mensaje): ?>
    <div class="alert alert-<?= htmlspecialchars($tipo_mensaje) ?> alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($mensaje) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<h1 class="mb-4">Dashboard</h1>

<!-- Tarjetas de métricas -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card text-bg-primary">
            <div class="card-body">
                <h5 class="card-title"><?= $metricas['total'] ?></h5>
                <p class="card-text">Total de postulaciones</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-success">
            <div class="card-body">
                <h5 class="card-title"><?= $metricas['activas'] ?></h5>
                <p class="card-text">Activas</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-warning">
            <div class="card-body">
                <h5 class="card-title"><?= $metricas['vencidas'] ?></h5>
                <p class="card-text">Vencidas</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card text-bg-danger">
            <div class="card-body">
                <h5 class="card-title"><?= $metricas['rechazadas'] ?></h5>
                <p class="card-text">Rechazadas</p>
            </div>
        </div>
    </div>
</div>

<!-- Formulario de búsqueda y filtros -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end" id="filtros">
            <div class="col-md-4">
                <label for="busqueda" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="busqueda" name="busqueda"
                       value="<?= htmlspecialchars($criterios['busqueda']) ?>"
                       placeholder="Empresa, puesto o plataforma...">
            </div>
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado">
                    <option value="">Todos</option>
                    <option value="Postulado" <?= $criterios['estado'] === 'Postulado' ? 'selected' : '' ?>>Postulado</option>
                    <option value="Vencido" <?= $criterios['estado'] === 'Vencido' ? 'selected' : '' ?>>Vencido</option>
                    <option value="Rechazado" <?= $criterios['estado'] === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="plataforma" class="form-label">Plataforma</label>
                <select class="form-select" id="plataforma" name="plataforma">
                    <option value="">Todas</option>
                    <?php foreach ($plataformas as $p): ?>
                        <option value="<?= htmlspecialchars($p) ?>" <?= $criterios['plataforma'] === $p ? 'selected' : '' ?>>
                            <?= htmlspecialchars($p) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-4">
                <a href="index.php" class="btn btn-outline-secondary w-100">Limpiar filtros</a>
            </div>
        </form>
    </div>
</div>

<!-- Postulaciones por plataforma -->
<?php if (!empty($metricas['por_plataforma'])): ?>
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Postulaciones por plataforma</div>
                <ul class="list-group list-group-flush">
                    <?php foreach ($metricas['por_plataforma'] as $plataforma => $cantidad): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= htmlspecialchars($plataforma) ?>
                            <span class="badge bg-primary rounded-pill"><?= $cantidad ?></span>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

<!--
  Contenedor de resultados.
  En la carga inicial se rellena con PHP (resultados_html).
  Cuando el usuario escribe o cambia un filtro, JavaScript (abajo)
  usa fetch() para obtener nuevo HTML de este mismo archivo (?ajax=1)
  y reemplaza el contenido de este <div> sin recargar la página.
-->
<div id="resultados">
    <?php resultados_html($postulaciones, $criterios); ?>
</div>

<script>
/**
 * Búsqueda dinámica con AJAX (fetch).
 *
 * ¿Por qué no usar el <form> directamente?
 * Si usáramos form.submit(), el navegador recargaría la página
 * y el input perdería el foco. Con fetch() obtenemos los datos
 * en segundo plano y solo reemplazamos la tabla.
 *
 * ¿Por qué separar el JS del HTML?
 * Antes los eventos estaban como onclick/oninput en las etiquetas HTML.
 * Al separarlos en un bloque <script>, el código es más fácil de
 * mantener y de entender.
 */
document.addEventListener('DOMContentLoaded', function () {
    const busqueda = document.getElementById('busqueda');
    const estado   = document.getElementById('estado');
    const plataforma = document.getElementById('plataforma');

    /**
     * Construye la URL con los filtros actuales, hace una petición
     * fetch() y reemplaza el contenido de #resultados.
     *
     * URLSearchParams: construye la query string de forma limpia,
     * evitando concatenar strings manualmente (que puede generar
     * errores de encoding).
     *
     * fetch(): API nativa de JavaScript para hacer peticiones HTTP.
     * Es más moderna y sencilla que XMLHttpRequest (XHR).
     * Devuelve una Promise, por eso usamos .then().
     */
    function filtrar() {
        const params = new URLSearchParams();
        params.set('ajax', '1'); // ← así PHP sabe que debe devolver solo la tabla
        if (busqueda.value)  params.set('busqueda', busqueda.value);
        if (estado.value)    params.set('estado', estado.value);
        if (plataforma.value) params.set('plataforma', plataforma.value);

        const url = 'index.php?' + params.toString();
        const resultados = document.getElementById('resultados');

        fetch(url)
            .then(r => r.text())       // convertir respuesta a texto HTML
            .then(html => {
                resultados.innerHTML = html;  // reemplazar contenido
            });
    }

    // ─── Buscador: debounce de 400ms ──────────────────────────────────
    // ¿Por qué debounce?
    // Si ejecutáramos filtrar() en cada tecla, haríamos una petición
    // por cada carácter escrito (ej: "Micro" → 5 peticiones).
    // Con debounce esperamos 400ms después de que el usuario deje de
    // escribir para hacer una sola petición con el término completo.
    // clearTimeout() cancela el timer anterior si el usuario sigue
    // escribiendo, reiniciando la espera.
    busqueda.addEventListener('input', function () {
        clearTimeout(this._timer);
        const input = this;
        input._timer = setTimeout(function () {
            filtrar();
        }, 400);
    });

    // ─── Filtros: actualizar al cambiar la selección ──────────────────
    // A diferencia del input, los <select> solo cambian cuando el
    // usuario elige una opción, así que no necesitan debounce.
    estado.addEventListener('change', filtrar);
    plataforma.addEventListener('change', filtrar);
});
</script>

<?php require_once __DIR__ . '/_footer.php'; ?>


<?php
// ─── Función auxiliar ───────────────────────────────────────────────────

/**
 * Renderiza el HTML de la lista de resultados (tabla o mensaje vacío).
 *
 * Separada como función para reutilizarla en dos contextos:
 *   1. Carga normal de la página (se llama al inicio, línea ~147)
 *   2. Respuesta AJAX (se llama desde el bloque "Modo AJAX", línea ~33)
 *
 * Esto evita duplicar el código de la tabla en dos lugares.
 *
 * @param array $postulaciones Lista de postulaciones (ya filtradas)
 * @param array $criterios     Filtros aplicados (para mostrar mensaje adecuado)
 */
function resultados_html(array $postulaciones, array $criterios): void {
    if (empty($postulaciones)): ?>
        <!--
          Si no hay resultados, mostramos un mensaje distinto según
          si el usuario aplicó filtros o el dashboard está vacío.
        -->
        <div class="alert alert-info">
            <?php if (!empty(array_filter($criterios))): ?>
                No se encontraron postulaciones con los filtros seleccionados.
            <?php else: ?>
                No hay postulaciones registradas.
                <a href="registrar.php" class="alert-link">Registrar la primera</a>.
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Empresa</th>
                        <th>Puesto</th>
                        <th>Plataforma</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($postulaciones as $p):
                        // match() de PHP 8 asigna un color Bootstrap según el estado real
                        $badge_color = match ($p['estado_real']) {
                            'Postulado' => 'success',
                            'Vencido' => 'warning',
                            'Rechazado' => 'danger',
                            default => 'secondary',
                        };
                    ?>
                        <tr>
                            <td><?= htmlspecialchars($p['empresa']) ?></td>
                            <td><?= htmlspecialchars($p['puesto']) ?></td>
                            <td><?= htmlspecialchars($p['plataforma']) ?></td>
                            <td><?= htmlspecialchars($p['fecha_postulacion']) ?></td>
                            <td><span class="badge bg-<?= $badge_color ?>"><?= $p['estado_real'] ?></span></td>
                            <td>
                                <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                                <a href="eliminar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif;
}
