<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Notas: <?= esc($periodo) ?> - Materia #<?= esc($materiaId) ?> - Ciclo #<?= esc($cicloId) ?></h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= base_url('notas/save') ?>" method="post">
    <input type="hidden" name="materia_id" value="<?= esc($materiaId) ?>">
    <input type="hidden" name="ciclo_id" value="<?= esc($cicloId) ?>">
    <input type="hidden" name="periodo" value="<?= esc($periodo) ?>">

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

<?= $this->endSection() ?>