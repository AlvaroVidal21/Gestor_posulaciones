<?php
/**
 * public/registrar.php
 * Formulario para registrar una nueva postulación.
 *
 * Si se envía por POST, valida los datos y los guarda en la BD.
 * Si se envía por GET, muestra el formulario vacío.
 *
 * El estado inicial siempre es "Postulado". Los estados "Vencido" y "Rechazado"
 * se asignan automáticamente o mediante edición posterior.
 *
 * Por qué validación del lado del servidor:
 * La validación HTML (required) es útil pero insuficiente.
 * Un usuario puede desactivar JS o enviar datos maliciosos.
 * La validación en PHP garantiza integridad de datos en la BD.
 */

require_once __DIR__ . '/../app/config.php';

// --- Procesar formulario si se envió por POST ---
$errores = [];
$datos = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Recolectar y limpiar entradas
    $datos['empresa'] = trim($_POST['empresa'] ?? '');
    $datos['puesto'] = trim($_POST['puesto'] ?? '');
    $datos['plataforma'] = trim($_POST['plataforma'] ?? '');
    $datos['url_oferta'] = trim($_POST['url_oferta'] ?? '');
    $datos['fecha_postulacion'] = $_POST['fecha_postulacion'] ?? '';
    $datos['notas'] = trim($_POST['notas'] ?? '');

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

    // Si no hay errores, guardar en BD
    if (empty($errores)) {
        try {
            $pdo = obtener_conexion();
            $stmt = $pdo->prepare('
                INSERT INTO postulaciones (empresa, puesto, plataforma, url_oferta, fecha_postulacion, fecha_ultima_actualizacion, estado, notas)
                VALUES (:empresa, :puesto, :plataforma, :url_oferta, :fecha_postulacion, :fecha_ultima_actualizacion, :estado, :notas)
            ');
            $stmt->execute([
                ':empresa' => $datos['empresa'],
                ':puesto' => $datos['puesto'],
                ':plataforma' => $datos['plataforma'],
                ':url_oferta' => $datos['url_oferta'] ?: null, // guarda null si está vacío
                ':fecha_postulacion' => $datos['fecha_postulacion'],
                ':fecha_ultima_actualizacion' => $datos['fecha_postulacion'], // inicialmente igual que fecha de postulación
                ':estado' => 'Postulado', // siempre se registra como Postulado
                ':notas' => $datos['notas'] ?: null,
            ]);

            // Redirigir al dashboard con mensaje de éxito
            header('Location: index.php?mensaje=Postulación+registrada+correctamente&tipo=success');
            exit;
        } catch (PDOException $e) {
            $errores[] = 'Error al guardar: ' . $e->getMessage();
        }
    }
}

// --- Mostrar formulario ---
$titulo = 'Nueva Postulación';
require_once __DIR__ . '/_header.php';
?>

<h1 class="mb-4"><?= $titulo ?></h1>

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
               value="<?= htmlspecialchars($datos['empresa'] ?? '') ?>" required>
    </div>

    <div class="col-md-6">
        <label for="puesto" class="form-label">Puesto <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="puesto" name="puesto"
               value="<?= htmlspecialchars($datos['puesto'] ?? '') ?>" required>
    </div>

    <div class="col-md-4">
        <label for="plataforma" class="form-label">Plataforma <span class="text-danger">*</span></label>
        <input type="text" class="form-control" id="plataforma" name="plataforma"
               value="<?= htmlspecialchars($datos['plataforma'] ?? '') ?>" required>
    </div>

    <div class="col-md-4">
        <label for="url_oferta" class="form-label">URL de la oferta</label>
        <input type="url" class="form-control" id="url_oferta" name="url_oferta"
               value="<?= htmlspecialchars($datos['url_oferta'] ?? '') ?>"
               placeholder="https://ejemplo.com/oferta">
    </div>

    <div class="col-md-4">
        <label for="fecha_postulacion" class="form-label">Fecha de postulación <span class="text-danger">*</span></label>
        <input type="date" class="form-control" id="fecha_postulacion" name="fecha_postulacion"
               value="<?= htmlspecialchars($datos['fecha_postulacion'] ?? date('Y-m-d')) ?>" required>
    </div>

    <div class="col-12">
        <label for="notas" class="form-label">Notas</label>
        <textarea class="form-control" id="notas" name="notas" rows="3"><?= htmlspecialchars($datos['notas'] ?? '') ?></textarea>
    </div>

    <div class="col-12">
        <button type="submit" class="btn btn-primary">Guardar Postulación</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </div>
</form>

<?php require_once __DIR__ . '/_footer.php'; ?>
