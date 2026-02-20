<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Crear materia</h1>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= base_url('materias/create') ?>" method="post" class="row g-3">
    <div class="col-md-12">
        <label class="form-label">Nombre</label>
        <input type="text" name="nombre_materia" class="form-control" required value="<?= esc(old('nombre_materia')) ?>">
    </div>
    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?= base_url('materias') ?>" class="btn btn-secondary ms-2">Cancelar</a>
    </div>
</form>
<?= $this->endSection() ?>
