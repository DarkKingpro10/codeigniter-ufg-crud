<form action="<?= base_url('alumnos/edit/' . $alumno['id']) ?>" method="post">
    <?= $this->extend('layouts/main') ?>

    <?= $this->section('content') ?>
    <h1 class="h3 mb-3">Editar alumno</h1>
    <form action="<?= base_url('alumnos/edit/' . $alumno['id']) ?>" method="post" class="row g-3">
        <div class="col-md-4">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" value="<?= esc($alumno['nombre']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Apellido</label>
            <input type="text" name="apellido" class="form-control" value="<?= esc($alumno['apellido']) ?>" required>
        </div>
        <div class="col-md-4">
            <label class="form-label">Teléfono</label>
            <input type="text" name="telefono" class="form-control" value="<?= esc($alumno['telefono']) ?>">
        </div>
        <div class="col-12 mt-3">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="<?= base_url('alumnos') ?>" class="btn btn-secondary ms-2">Cancelar</a>
        </div>
    </form>
    <?= $this->endSection() ?>
    