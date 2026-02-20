<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Asignar materias por docente (máx <?= esc($maxMaterias) ?>)</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= base_url('horarios/asignar') ?>" method="post" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Docente</label>
        <select name="id_docente" class="form-select" required>
            <option value="">Seleccione un docente</option>
            <?php foreach ($docentes as $d): ?>
                <option value="<?= esc($d['id_docente']) ?>" <?= (string) old('id_docente') === (string) $d['id_docente'] ? 'selected' : '' ?>>
                    <?= esc($d['nombre_docente']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="col-12">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-light text-center">
                    <tr>
                        <th style="width: 40%">Materia</th>
                        <th style="width: 20%">Día</th>
                        <th style="width: 20%">Hora inicio</th>
                        <th style="width: 20%">Hora fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $diasOpciones = ['Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo'];
                    ?>
                    <?php for ($i = 0; $i < (int) $maxMaterias; $i++): ?>
                        <tr>
                            <td>
                                <select name="id_materia[]" class="form-select">
                                    <option value="">-- ninguna --</option>
                                    <?php foreach ($materias as $m): ?>
                                        <option value="<?= esc($m['id_materia']) ?>" <?= (string) (old('id_materia.' . $i) ?? '') === (string) $m['id_materia'] ? 'selected' : '' ?>>
                                            <?= esc($m['nombre_materia']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <select name="dia[]" class="form-select">
                                    <option value="">Seleccione</option>
                                    <?php foreach ($diasOpciones as $dia): ?>
                                        <option value="<?= esc($dia) ?>" <?= (string) (old('dia.' . $i) ?? '') === (string) $dia ? 'selected' : '' ?>>
                                            <?= esc($dia) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td>
                                <input type="time" name="hora_inicio[]" class="form-control" value="<?= esc(old('hora_inicio.' . $i) ?? '') ?>">
                            </td>
                            <td>
                                <input type="time" name="hora_fin[]" class="form-control" value="<?= esc(old('hora_fin.' . $i) ?? '') ?>">
                            </td>
                        </tr>
                    <?php endfor; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="col-12 mt-2">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?= base_url('horarios') ?>" class="btn btn-secondary ms-2">Cancelar</a>
    </div>
</form>
<?= $this->endSection() ?>
