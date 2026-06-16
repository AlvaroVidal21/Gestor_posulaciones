<?php
/**
 * public/vencidas.php
 * Vista dedicada a postulaciones vencidas.
 *
 * CU-007: Visualizar Postulaciones Vencidas (docs/04_casos_uso.md)
 *
 * Muestra solo las postulaciones cuyo estado real es "Vencido",
 * permitiendo identificar rápidamente cuáles requieren atención.
 *
 * Nota: también se puede acceder desde el dashboard aplicando
 * el filtro de estado "Vencido" (index.php?estado=Vencido).
 */

require_once __DIR__ . '/../app/postulacion.php';

$postulaciones = buscar_postulaciones(['estado' => 'Vencido']);

$titulo = 'Postulaciones Vencidas';
require_once __DIR__ . '/_header.php';
?>

<h1 class="mb-4">Postulaciones Vencidas</h1>

<p class="text-muted">
    Postulaciones que superaron los 15 días sin actualización desde
    su última modificación y necesitan seguimiento.
</p>

<?php if (empty($postulaciones)): ?>
    <div class="alert alert-success">
        No hay postulaciones vencidas. ¡Buen trabajo!
    </div>
<?php else: ?>
    <div class="d-none d-md-block">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>Empresa</th>
                    <th>Puesto</th>
                    <th>Plataforma</th>
                    <th>Última actualización</th>
                    <th>Días sin actualizar</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($postulaciones as $p): ?>
                    <?php
                        $fecha_act = new DateTime($p['fecha_ultima_actualizacion']);
                        $dias = $fecha_act->diff(new DateTime())->days;
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($p['empresa']) ?></td>
                        <td><?= htmlspecialchars($p['puesto']) ?></td>
                        <td><?= badge_plataforma_html($p['plataforma']) ?></td>
                        <td><span class="fecha-legible"><?= htmlspecialchars(formatear_fecha_corta($p['fecha_ultima_actualizacion'])) ?></span></td>
                        <td><span class="badge bg-warning"><?= $dias ?> días</span></td>
                        <td>
                            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-action btn-action-edit btn-action-text">
                                <span aria-hidden="true">✎</span>
                                <span>Actualizar</span>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="resultados-tarjetas d-md-none">
        <?php foreach ($postulaciones as $p): ?>
            <?php
                $fecha_act = new DateTime($p['fecha_ultima_actualizacion']);
                $dias = $fecha_act->diff(new DateTime())->days;
            ?>
            <article class="postulacion-card">
                <div class="postulacion-card__encabezado">
                    <h2 class="postulacion-card__titulo"><?= htmlspecialchars($p['empresa']) ?></h2>
                    <p class="postulacion-card__puesto"><?= htmlspecialchars($p['puesto']) ?></p>
                </div>

                <div class="postulacion-card__datos">
                    <div class="postulacion-card__fila">
                        <span class="postulacion-card__etiqueta">Plataforma</span>
                        <span class="postulacion-card__valor"><?= badge_plataforma_html($p['plataforma']) ?></span>
                    </div>
                    <div class="postulacion-card__fila">
                        <span class="postulacion-card__etiqueta">Última actualización</span>
                        <span class="postulacion-card__valor fecha-legible"><?= htmlspecialchars(formatear_fecha_corta($p['fecha_ultima_actualizacion'])) ?></span>
                    </div>
                    <div class="postulacion-card__fila">
                        <span class="postulacion-card__etiqueta">Sin actualizar</span>
                        <span class="postulacion-card__valor">
                            <span class="badge bg-warning"><?= $dias ?> días</span>
                        </span>
                    </div>
                </div>

                <div class="postulacion-card__acciones">
                    <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-action btn-action-edit btn-action-text w-100">
                        <span aria-hidden="true">✎</span>
                        <span>Actualizar</span>
                    </a>
                </div>
            </article>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">Volver al Dashboard</a>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>

<?php
/**
 * Renderiza un badge visual para plataforma.
 *
 * @param string $plataforma Nombre de plataforma
 * @return string HTML seguro del badge
 */
function badge_plataforma_html(string $plataforma): string {
    $color = obtener_color_plataforma($plataforma);
    return '<span class="badge plataforma-badge text-bg-' . htmlspecialchars($color) . '">'
        . htmlspecialchars($plataforma)
        . '</span>';
}
?>
