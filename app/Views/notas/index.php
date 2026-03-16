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
                <option value="<?= esc($m['id_materia']) ?>" <?= isset($selectedMateria) && $selectedMateria == $m['id_materia'] ? 'selected' : '' ?>><?= esc($m['nombre_materia']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Ciclo</label>
        <select name="ciclo_id" class="form-select" required>
            <option value="">Seleccione ciclo</option>
            <?php foreach ($ciclos as $c): ?>
                <option value="<?= esc($c['id_ciclo']) ?>" <?= isset($selectedCiclo) && $selectedCiclo == $c['id_ciclo'] ? 'selected' : '' ?>><?= esc($c['nombre']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Nota</label>
        <select name="periodo" class="form-select" required>
            <option value="">Seleccione periodo</option>
            <?php foreach (($periodos ?? []) as $p): ?>
                <option value="<?= esc($p) ?>" <?= isset($selectedPeriodo) && $selectedPeriodo == $p ? 'selected' : '' ?>><?= esc($p) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-2 align-self-end">
        <button class="btn btn-primary">Abrir</button>
    </div>
</form>

<?php if (isset($alumnos) && is_array($alumnos)): ?>
    <hr>
    <h1 class="h3 mb-3">Notas: <?= esc($selectedPeriodo) ?> - Materia #<?= esc($selectedMateria) ?> - Ciclo #<?= esc($selectedCiclo) ?></h1>

    <form action="<?= base_url('notas/save') ?>" method="post">
        <input type="hidden" name="materia_id" value="<?= esc($selectedMateria) ?>">
        <input type="hidden" name="ciclo_id" value="<?= esc($selectedCiclo) ?>">
        <input type="hidden" name="periodo" value="<?= esc($selectedPeriodo) ?>">

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Apellido y nombre</th>
                    <th>Nota</th>
                    <th>Observaciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($alumnos as $a):
                    $alId = $a['id'];
                    $nota = isset($notasMap[$alId]) ? $notasMap[$alId]['nota'] : '';
                    $obs = isset($notasMap[$alId]) ? $notasMap[$alId]['observaciones'] : '';
                ?>
                    <tr>
                        <td><?= esc($a['codigo']) ?></td>
                        <td><?= esc($a['apellido'] . ' ' . $a['nombre']) ?></td>
                        <td style="width:120px;"><input type="text" name="nota[<?= $alId ?>]" value="<?= esc($nota) ?>" class="form-control form-control-sm"></td>
                        <td><input type="text" name="observaciones[<?= $alId ?>]" value="<?= esc($obs) ?>" class="form-control form-control-sm"></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="mt-3">
            <button class="btn btn-success">Guardar notas</button>
            <a href="<?= base_url('notas') ?>" class="btn btn-secondary ms-2">Volver</a>
        </div>
    </form>
<?php endif; ?>

<?= $this->endSection() ?>