<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Editar inscripción</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= base_url('inscripciones/edit/' . $inscripcion['id_inscripcion']) ?>" method="post" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Alumno</label>
        <select name="alumno_id" class="form-select" required>
            <option value="">Seleccione un alumno</option>
            <?php
            $selectedAlumno = old('alumno_id') ?? $inscripcion['alumno_id'];
            ?>
            <?php foreach ($alumnos as $a): ?>
                <option value="<?= esc($a['id']) ?>" <?= (string) $selectedAlumno === (string) $a['id'] ? 'selected' : '' ?>>
                    <?= esc($a['codigo'] . ' - ' . $a['apellido'] . ' ' . $a['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Horario</label>
        <select name="horario_id" class="form-select" required>
            <option value="">Seleccione un horario</option>
            <?php
            $selectedHorario = old('horario_id') ?? $inscripcion['horario_id'];
            ?>
            <?php foreach ($horarios as $h): ?>
                <option value="<?= esc($h['id']) ?>" <?= (string) $selectedHorario === (string) $h['id'] ? 'selected' : '' ?>>
                    <?= esc($h['nombre_docente'] . ' | ' . $h['nombre_materia'] . ' | ' . $h['dia'] . ' ' . $h['hora_inicio'] . '-' . $h['hora_fin']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?= base_url('inscripciones') ?>" class="btn btn-secondary ms-2">Cancelar</a>
    </div>
</form>
<?= $this->endSection() ?>