<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Crear alumno</h1>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<form action="<?= base_url('alumnos/create') ?>" method="post" class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Código</label>
        <input type="text" name="codigo" class="form-control" required value="<?= esc(old('codigo')) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" required value="<?= esc(old('nombre')) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Apellido</label>
        <input type="text" name="apellido" class="form-control" required value="<?= esc(old('apellido')) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Teléfono</label>
        <input type="number" name="telefono" class="form-control" value="<?= esc(old('telefono')) ?>">
    </div>
    <div class="col-md-6">
        <label class="form-label">Carrera</label>
        <select name="codigo_carrera" class="form-select" required>
            <option value="">Seleccione una carrera</option>
            <?php foreach ($carreras as $carrera): ?>
                <option value="<?= esc($carrera['codigo_carrera']) ?>" <?= (string) old('codigo_carrera') === (string) $carrera['codigo_carrera'] ? 'selected' : '' ?>>
                    <?= esc($carrera['nombre_carrera']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?= base_url('alumnos') ?>" class="btn btn-secondary ms-2">Cancelar</a>
    </div>
</form>
<?= $this->endSection() ?>