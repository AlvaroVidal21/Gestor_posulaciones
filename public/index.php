<?php
/**
 * public/index.php
 * Dashboard principal del Gestor de Postulaciones.
 *
 * Muestra métricas agregadas (sin filtrar) y la lista de postulaciones.
 * La lista se puede buscar y filtrar mediante controles en la interfaz.
 *
 * CU-002: Visualizar Dashboard
 * CU-003: Buscar postulaciones
 * CU-004: Filtrar postulaciones
 *
 * (docs/04_casos_uso.md)
 */

require_once __DIR__ . '/../app/postulacion.php';

// Leer criterios de búsqueda/filtro desde GET
$criterios = [
    'busqueda' => $_GET['busqueda'] ?? '',
    'estado' => $_GET['estado'] ?? '',
    'empresa' => $_GET['empresa'] ?? '',
    'plataforma' => $_GET['plataforma'] ?? '',
];

$metricas = obtener_metricas();
$postulaciones = buscar_postulaciones($criterios);

// Obtener lista de plataformas únicas para el filtro
$plataformas = array_keys($metricas['por_plataforma']);

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

<!-- Formulario de búsqueda y filtros (filtrado automático al cambiar) -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-2 align-items-end" id="filtros">
            <div class="col-md-4">
                <label for="busqueda" class="form-label">Buscar</label>
                <input type="text" class="form-control" id="busqueda" name="busqueda"
                       value="<?= htmlspecialchars($criterios['busqueda']) ?>"
                       placeholder="Empresa, puesto o plataforma..."
                       oninput="clearTimeout(this._timer); this._timer=setTimeout(()=>this.form.submit(), 400)">
            </div>
            <div class="col-md-2">
                <label for="estado" class="form-label">Estado</label>
                <select class="form-select" id="estado" name="estado" onchange="this.form.submit()">
                    <option value="">Todos</option>
                    <option value="Postulado" <?= $criterios['estado'] === 'Postulado' ? 'selected' : '' ?>>Postulado</option>
                    <option value="Vencido" <?= $criterios['estado'] === 'Vencido' ? 'selected' : '' ?>>Vencido</option>
                    <option value="Rechazado" <?= $criterios['estado'] === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="plataforma" class="form-label">Plataforma</label>
                <select class="form-select" id="plataforma" name="plataforma" onchange="this.form.submit()">
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

<!-- Lista de postulaciones -->
<?php if (empty($postulaciones)): ?>
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
                <?php foreach ($postulaciones as $p): ?>
                    <?php
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
                        <td>
                            <span class="badge bg-<?= $badge_color ?>">
                                <?= $p['estado_real'] ?>
                            </span>
                        </td>
                        <td>
                            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">Editar</a>
                            <a href="eliminar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-danger">Eliminar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/_footer.php'; ?>
