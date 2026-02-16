<?php

/** Layout principal con Bootstrap */ ?>
<!doctype html>
<html lang="es" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <title><?= isset($title) ? esc($title) : 'Prueba CRUD' ?></title>
    <?= $this->renderSection('styles') ?>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container">
                <a class="navbar-brand" href="#">CRUD - CodeIgniter</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php
                        $currentUri = uri_string();
                        $isAlumnosList = ($currentUri === 'alumnos' || $currentUri === 'public/alumnos' || rtrim($currentUri, '/') === 'alumnos');
                        $isAlumnosCarrera = ($currentUri === 'alumnos_carrera' || $currentUri === 'public/alumnos_carrera' || rtrim($currentUri, '/') === 'alumnos_carrera');
                        ?>
                        <li class="nav-item">
                            <a class="nav-link <?= $isAlumnosList ? 'active' : '' ?>" href="<?= base_url('alumnos') ?>">Listado de alumnos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= $isAlumnosCarrera ? 'active' : '' ?>" href="<?= base_url('alumnos_carrera') ?>">Alumnos por carrera</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= ($currentUri === 'alumnos/create' || $currentUri === 'public/alumnos/create') ? 'active' : '' ?>" href="<?= base_url('alumnos/create') ?>">Crear alumno</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container mt-4">
        <?= $this->renderSection('content') ?>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
    <?= $this->renderSection('scripts') ?>
</body>

</html>