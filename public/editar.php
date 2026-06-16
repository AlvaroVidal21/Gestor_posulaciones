<?php
/**
 * public/editar.php
 * Formulario de edición de una postulación existente.
 *
 * CU-005: Actualizar Postulación (docs/04_casos_uso.md)
 *
 * Si se envía por POST, valida y actualiza los datos en la BD.
 * Cada actualización modifica fecha_ultima_actualizacion, lo que
 * reinicia el contador de vencimiento de 15 días (Regla 5 y 6).
 */

require_once __DIR__ . '/../app/postulacion.php';

// Obtener ID de la postulación a editar
$id = (int) ($_GET['id'] ?? 0);
$postulacion = obtener_por_id($id);

// Si no existe la postulación, redirigir al dashboard
if (!$postulacion) {
    header('Location: index.php?mensaje=Postulación+no+encontrada&tipo=danger');
    exit;
}

$errores = [];

// --- Procesar formulario si se envió por POST ---
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'empresa' => trim($_POST['empresa'] ?? ''),
        'puesto' => trim($_POST['puesto'] ?? ''),
        'plataforma' => normalizar_plataforma($_POST['plataforma'] ?? ''),
        'url_oferta' => trim($_POST['url_oferta'] ?? ''),
        'fecha_postulacion' => $_POST['fecha_postulacion'] ?? '',
        'estado' => $_POST['estado'] ?? '',
        'notas' => trim($_POST['notas'] ?? ''),
    ];

    // Validar campos obligatorios
    if ($datos['empresa'] === '') {
        $errores[] = 'El campo "Empresa" es obligatorio.';
    }
    if ($datos['puesto'] === '') {
        $errores[] = 'El campo "Puesto" es obligatorio.';
    }
    if ($datos['plataforma'] === '') {
        $errores[] = 'El campo "Plataforma" es obligatorio.';
    }
    if ($datos['fecha_postulacion'] === '') {
        $errores[] = 'La fecha de postulación es obligatoria.';
    }
    if (!in_array($datos['estado'], ['Postulado', 'Rechazado'], true)) {
        $errores[] = 'El estado seleccionado no es válido.';
    }

    if (empty($errores)) {
        try {
            actualizar_postulacion($id, $datos);
            header('Location: index.php?mensaje=Postulación+actualizada+correctamente&tipo=success');
            exit;
        } catch (PDOException $e) {
            $errores[] = 'Error al actualizar: ' . $e->getMessage();
        }
    }
} else {
    // En GET, usar datos existentes como valores por defecto
    $datos = $postulacion;
}

$plataformas = obtener_plataformas();
$titulo = 'Editar Postulación';
require_once __DIR__ . '/_header.php';
?>

<h1 class="mb-4"><?= $titulo ?></h1>

<!-- Mostrar estado actual como alerta informativa -->
<div class="alert alert-info">
    Estado actual: <strong><?= $postulacion['estado_real'] ?></strong>.
    Al guardar los cambios, la fecha de última actualización se renovará automáticamente.
</div>

<?php if (!empty($errores)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errores as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="POST" class="row g-3">
    <div class="col-md-6">
        <label for="empresa" class="form-label">Empresa <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="empresa" name="empresa"
               value="<?= htmlspecialchars($datos['empresa']) ?>" required>
    </div>

    <div class="col-md-6">
        <label for="puesto" class="form-label">Puesto <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="puesto" name="puesto"
               value="<?= htmlspecialchars($datos['puesto']) ?>" required>
    </div>

    <div class="col-md-4">
        <label for="plataforma" class="form-label">Plataforma <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="plataforma" name="plataforma"
               value="<?= htmlspecialchars($datos['plataforma']) ?>"
               list="lista-plataformas" required>
        <datalist id="lista-plataformas">
            <?php foreach ($plataformas as $plataforma): ?>
                <option value="<?= htmlspecialchars($plataforma) ?>"></option>
            <?php endforeach; ?>
        </datalist>
    </div>

    <div class="col-md-4">
        <label for="url_oferta" class="form-label">URL de la oferta</label>
        <input type="url" class="form-control" id="url_oferta" name="url_oferta"
               value="<?= htmlspecialchars($datos['url_oferta'] ?? '') ?>"
               placeholder="https://ejemplo.com/oferta">
    </div>

    <div class="col-md-2">
        <label for="fecha_postulacion" class="form-label">Fecha de postulación <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="fecha_postulacion" name="fecha_postulacion"
               value="<?= htmlspecialchars($datos['fecha_postulacion']) ?>" required>
    </div>

    <div class="col-md-2">
        <label for="estado" class="form-label">Estado <span class="text-danger">*</span></label>
        <select class="form-select" id="estado" name="estado" required>
            <option value="Postulado" <?= $datos['estado'] === 'Postulado' ? 'selected' : '' ?>>Postulado</option>
            <option value="Rechazado" <?= $datos['estado'] === 'Rechazado' ? 'selected' : '' ?>>Rechazado</option>
        </select>
    </div>

    <div class="col-12">
        <label for="notas" class="form-label">Notas</label>
        <textarea class="form-control" id="notas" name="notas" rows="3"><?= htmlspecialchars($datos['notas'] ?? '') ?></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/_footer.php'; ?>
