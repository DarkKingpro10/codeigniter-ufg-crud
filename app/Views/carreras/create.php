<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Crear carrera</h1>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= base_url('carreras/create') ?>" method="post" class="row g-3">
    <div class="col-md-3">
        <label class="form-label">Código (opcional)</label>
        <input type="number" name="codigo_carrera" class="form-control" value="<?= esc(old('codigo_carrera')) ?>" min="1">
    </div>
    <div class="col-md-9">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre_carrera" class="form-control" required value="<?= esc(old('nombre_carrera')) ?>">
    </div>
    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?= base_url('carreras') ?>" class="btn btn-secondary ms-2">Cancelar</a>
    </div>
</form>
<?= $this->endSection() ?>