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
    <div class="table-responsive">
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
                        <td><?= htmlspecialchars($p['plataforma']) ?></td>
                        <td><?= htmlspecialchars($p['fecha_ultima_actualizacion']) ?></td>
                        <td><span class="badge bg-warning"><?= $dias ?> días</span></td>
                        <td>
                            <a href="editar.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-primary">
                                Actualizar
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<div class="mt-3">
    <a href="index.php" class="btn btn-secondary">Volver al Dashboard</a>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
