<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Notas por materia</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= base_url('notas') ?>" method="get" class="row g-3">
    <div class="col-md-4">
        <label class="form-label">Materia</label>
        <select name="materia_id" class="form-select" required>
            <option value="">Seleccione una materia</option>
            <?php foreach ($materias as $m): ?>
                <option value="<?= esc($m['id_materia']) ?>"><?= esc($m['nombre_materia']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Ciclo</label>
        <select name="ciclo_id" class="form-select" required>
            <option value="">Seleccione ciclo</option>
            <?php foreach ($ciclos as $c): ?>
                <option value="<?= esc($c['id_ciclo']) ?>"><?= esc($c['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Periodo</label>
        <select name="periodo" class="form-select" required>
            <option value="">Seleccione periodo</option>
            <?php foreach (($periodos ?? []) as $p): ?>
                <option value="<?= esc($p) ?>"><?= esc($p) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 align-self-end">
        <button class="btn btn-primary">Abrir</button>
    </div>
</form>

<?= $this->endSection() ?>
