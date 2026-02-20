<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Editar alumno</h1>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>
<form action="<?= base_url('alumnos/edit/' . $alumno['id']) ?>" method="post" class="row">
    <div class="col-md-3">
        <label class="form-label">Código</label>
        <input type="text" name="codigo" class="form-control" required value="<?= esc(old('codigo') ?? ($alumno['codigo'] ?? '')) ?>">
    </div>
    <div class="col-md-3">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre" class="form-control" value="<?= esc(old('nombre') ?? $alumno['nombre']) ?>" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Apellido</label>
        <input type="text" name="apellido" class="form-control" value="<?= esc(old('apellido') ?? $alumno['apellido']) ?>" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Teléfono</label>
        <input type="text" name="telefono" class="form-control" value="<?= esc(old('telefono') ?? $alumno['telefono']) ?>">
    </div>
    <div class="col-md-6 mt-3">
        <label class="form-label">Carrera</label>
        <?php $selectedCarrera = old('codigo_carrera') ?? ($alumno['codigo_carrera'] ?? ''); ?>
        <select name="codigo_carrera" class="form-select" required>
            <option value="">Seleccione una carrera</option>
            <?php foreach ($carreras as $carrera): ?>
                <option value="<?= esc($carrera['codigo_carrera']) ?>" <?= (string) $selectedCarrera === (string) $carrera['codigo_carrera'] ? 'selected' : '' ?>>
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