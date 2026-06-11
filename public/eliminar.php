<?php
/**
 * public/eliminar.php
 * Confirmación y eliminación de una postulación.
 *
 * CU-006: Eliminar Postulación (docs/04_casos_uso.md)
 *
 * Flujo:
 * 1. GET: muestra confirmación con datos de la postulación
 * 2. POST (con confirmación): elimina y redirige al dashboard
 *
 * Por qué usar POST para eliminar:
 * Las peticiones GET no deberían modificar datos (principio REST).
 * Con POST se evita que un bot o precarga accidental elimine registros.
 */

require_once __DIR__ . '/../app/postulacion.php';

$id = (int) ($_GET['id'] ?? 0);
$postulacion = obtener_por_id($id);

if (!$postulacion) {
    header('Location: index.php?mensaje=Postulación+no+encontrada&tipo=danger');
    exit;
}

// --- Procesar eliminación si se confirmó por POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $confirmado = $_POST['confirmar'] ?? false;

    if ($confirmado) {
        eliminar_postulacion($id);
        header('Location: index.php?mensaje=Postulación+eliminada+correctamente&tipo=success');
        exit;
    }

    // Si no confirmó, redirigir al dashboard
    header('Location: index.php');
    exit;
}

$titulo = 'Eliminar Postulación';
require_once __DIR__ . '/_header.php';
?>

<h1 class="mb-4"><?= $titulo ?></h1>

<div class="card border-danger">
    <div class="card-header bg-danger text-white">
        ¿Estás seguro de eliminar esta postulación?
    </div>
    <div class="card-body">
        <p class="card-text">Esta acción no se puede deshacer.</p>

        <dl class="row">
            <dt class="col-sm-2">Empresa</dt>
            <dd class="col-sm-10"><?= htmlspecialchars($postulacion['empresa']) ?></dd>

            <dt class="col-sm-2">Puesto</dt>
            <dd class="col-sm-10"><?= htmlspecialchars($postulacion['puesto']) ?></dd>

            <dt class="col-sm-2">Plataforma</dt>
            <dd class="col-sm-10"><?= htmlspecialchars($postulacion['plataforma']) ?></dd>

            <dt class="col-sm-2">Estado</dt>
            <dd class="col-sm-10"><?= $postulacion['estado_real'] ?></dd>

            <dt class="col-sm-2">Fecha</dt>
            <dd class="col-sm-10"><?= htmlspecialchars($postulacion['fecha_postulacion']) ?></dd>
        </dl>

        <form method="POST">
            <input type="hidden" name="confirmar" value="1">
            <button type="submit" class="btn btn-danger">Sí, eliminar</button>
            <a href="index.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/_footer.php'; ?>
