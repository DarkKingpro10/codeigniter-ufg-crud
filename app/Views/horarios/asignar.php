<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Asignar materias por docente (máx <?= esc($maxMaterias) ?>)</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= base_url('horarios/asignar') ?>" method="post" class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Docente</label>
        <select id="select-docente" name="id_docente" class="form-select" required>
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
                    $diasOpciones = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];
                    ?>
                    <?php for ($i = 0; $i < (int) $maxMaterias; $i++): ?>
                        <tr>
                            <input type="hidden" name="horario_id[]" value="<?= esc(old('horario_id.' . $i) ?? '') ?>">
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
<?php /* horariosPorDocente se genera en el controlador */ ?>

<script>
    const horariosPorDocente = <?= json_encode($horariosPorDocente ?? [], JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP) ?>;

    document.addEventListener('DOMContentLoaded', function() {
        const docenteSelect = document.getElementById('select-docente');
        const filas = document.querySelectorAll('table tbody tr');

        function limpiarFilas() {
            filas.forEach(function(row) {
                const materia = row.querySelector('select[name="id_materia[]"]');
                const dia = row.querySelector('select[name="dia[]"]');
                const hi = row.querySelector('input[name="hora_inicio[]"]');
                const hf = row.querySelector('input[name="hora_fin[]"]');
                if (materia) materia.value = '';
                if (dia) dia.value = '';
                if (hi) hi.value = '';
                if (hf) hf.value = '';
            });
        }

        docenteSelect.addEventListener('change', function() {
            const id = this.value ? parseInt(this.value, 10) : 0;
            if (!id || !horariosPorDocente[id] || horariosPorDocente[id].length === 0) {
                limpiarFilas();
                return;
            }

            const data = horariosPorDocente[id];

            for (let i = 0; i < filas.length; i++) {
                const row = filas[i];
                const materia = row.querySelector('select[name="id_materia[]"]');
                const dia = row.querySelector('select[name="dia[]"]');
                const hi = row.querySelector('input[name="hora_inicio[]"]');
                const hf = row.querySelector('input[name="hora_fin[]"]');
                const hid = row.querySelector('input[name="horario_id[]"]');

                if (data[i]) {
                    if (materia) materia.value = data[i].id_materia ?? '';
                    if (dia) dia.value = data[i].dia ?? '';
                    if (hi) hi.value = (data[i].hora_inicio ?? '').slice(0, 5);
                    if (hf) hf.value = (data[i].hora_fin ?? '').slice(0, 5);
                    if (hid) hid.value = data[i].id ?? '';
                } else {
                    if (materia) materia.value = '';
                    if (dia) dia.value = '';
                    if (hi) hi.value = '';
                    if (hf) hf.value = '';
                    if (hid) hid.value = '';
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>