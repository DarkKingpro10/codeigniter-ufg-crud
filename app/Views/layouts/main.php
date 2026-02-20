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
        <nav class="navbar navbar-expand-lg bg-body-tertiary border-bottom">
            <div class="container">
                <a class="navbar-brand fw-semibold" href="<?= base_url('/') ?>">CRUD - CodeIgniter</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <?php
                        $currentUri = trim(uri_string(), '/');
                        $currentUri = preg_replace('#^public/#', '', $currentUri);

                        $isAlumnosList = ($currentUri === 'alumnos' || $currentUri === '');
                        $isAlumnosCreate = ($currentUri === 'alumnos/create');
                        $isAlumnosCarrera = ($currentUri === 'alumnos_carrera');
                        $isAlumnosMateria = ($currentUri === 'alumnos_materia');

                        $isCarreras = ($currentUri === 'carreras');
                        $isMaterias = ($currentUri === 'materias');
                        $isDocentes = ($currentUri === 'docentes');

                        $isHorarios = ($currentUri === 'horarios' || $currentUri === 'horarios/asignar');
                        $isInscripciones = ($currentUri === 'inscripciones');

                        $isAlumnosMenu = ($isAlumnosList || $isAlumnosCreate || $isAlumnosCarrera || $isAlumnosMateria);
                        $isCatalogosMenu = ($isCarreras || $isMaterias || $isDocentes);
                        $isHorariosMenu = ($isHorarios || $isInscripciones);
                        ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= $isAlumnosMenu ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Alumnos
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item <?= $isAlumnosList ? 'active' : '' ?>" href="<?= base_url('alumnos') ?>">Listado</a></li>
                                <li><a class="dropdown-item <?= $isAlumnosCreate ? 'active' : '' ?>" href="<?= base_url('alumnos/create') ?>">Crear</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item <?= $isAlumnosCarrera ? 'active' : '' ?>" href="<?= base_url('alumnos_carrera') ?>">Por carrera</a></li>
                                <li><a class="dropdown-item <?= $isAlumnosMateria ? 'active' : '' ?>" href="<?= base_url('alumnos_materia') ?>">Por materia</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= $isCatalogosMenu ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Catálogos
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item <?= $isCarreras ? 'active' : '' ?>" href="<?= base_url('carreras') ?>">Carreras</a></li>
                                <li><a class="dropdown-item <?= $isMaterias ? 'active' : '' ?>" href="<?= base_url('materias') ?>">Materias</a></li>
                                <li><a class="dropdown-item <?= $isDocentes ? 'active' : '' ?>" href="<?= base_url('docentes') ?>">Docentes</a></li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle <?= $isHorariosMenu ? 'active' : '' ?>" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Horarios
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item <?= $isHorarios ? 'active' : '' ?>" href="<?= base_url('horarios') ?>">Materias por docente</a></li>
                                <li><a class="dropdown-item <?= $isInscripciones ? 'active' : '' ?>" href="<?= base_url('inscripciones') ?>">Inscripciones</a></li>
                            </ul>
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