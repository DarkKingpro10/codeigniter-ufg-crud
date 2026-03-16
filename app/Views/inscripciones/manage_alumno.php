<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Gestionar inscripciones de alumno</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="card mb-3">
    <div class="card-body">
        <h5 class="card-title"><?= esc($alumno['apellido'] . ' ' . $alumno['nombre']) ?></h5>
        <p class="card-text">Código: <?= esc($alumno['codigo'] ?? '') ?></p>
    </div>
</div>

<form action="<?= base_url('inscripciones/alumno/' . $alumno['id']) ?>" method="post" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Ciclo</label>
        <select name="ciclo_id" class="form-select">
            <option value="">(Seleccione ciclo)</option>
            <?php foreach ($ciclos as $c): ?>
                <option value="<?= esc($c['id_ciclo']) ?>" <?= (string) ($ciclo_id ?? '') === (string) $c['id_ciclo'] ? 'selected' : '' ?>>
                    <?= esc($c['nombre'] . (isset($c['activo']) && $c['activo'] ? ' (activo)' : '')) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12">
        <label class="form-label">Horarios (máx 5)</label>
        <div class="list-group horarios-list" style="max-height:300px;overflow:auto;">
            <?php
            $inscrHorarios = array_column($inscripciones, 'horario_id');
            foreach ($horarios as $h): ?>
                <label class="list-group-item">
                    <input type="checkbox" name="horario_id[]" value="<?= esc($h['id']) ?>" class="form-check-input me-2 horario-checkbox" <?= in_array($h['id'], $inscrHorarios) ? 'checked' : '' ?>>
                    <?= esc($h['nombre_docente'] . ' | ' . $h['nombre_materia'] . ' | ' . $h['dia'] . ' ' . $h['hora_inicio'] . '-' . $h['hora_fin']) ?>
                </label>
            <?php endforeach; ?>
        </div>
        <div class="form-text" id="horario-help"></div>
    </div>

    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-success">Actualizar inscripciones</button>
        <a href="<?= base_url('inscripciones') ?>" class="btn btn-secondary ms-2">Volver</a>
    </div>
</form>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.horario-checkbox');

        function updateCount() {
            const checked = document.querySelectorAll('.horario-checkbox:checked').length;
            const help = document.getElementById('horario-help');
            help.textContent = checked + ' seleccionado(s) (máx 5)';
        }
        checkboxes.forEach(cb => cb.addEventListener('change', function() {
            const checked = document.querySelectorAll('.horario-checkbox:checked').length;
            if (checked > 5) {
                this.checked = false;
                return alert('Máximo 5 horarios.');
            }
            updateCount();
        }));
        updateCount();
    });
</script>

<?= $this->endSection() ?>