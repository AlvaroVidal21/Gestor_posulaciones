<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestor de Postulaciones</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            box-shadow: 0 2px 8px rgba(0,0,0,.15);
        }

        /* Espaciado entre enlaces de navegación */
        .navbar-nav .nav-item {
            margin-left: .5rem;
        }
        .navbar-nav .nav-link.active {
            text-decoration: underline;
            text-underline-offset: 4px;
        }
    </style>
</head>
<body>
    <?php
        // Determinar la página activa para resaltar el nav-link correspondiente
        $pagina_actual = basename($_SERVER['SCRIPT_NAME']);
    ?>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                Gestor de Postulaciones
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav"
                    aria-controls="menuNav" aria-expanded="false" aria-label="Abrir menú">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="menuNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link <?= $pagina_actual === 'index.php' ? 'active' : '' ?>" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pagina_actual === 'registrar.php' ? 'active' : '' ?>" href="registrar.php">Nueva Postulación</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?= $pagina_actual === 'vencidas.php' ? 'active' : '' ?>" href="vencidas.php">Vencidas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main class="container">
