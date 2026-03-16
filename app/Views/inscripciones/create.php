<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<h1 class="h3 mb-3">Crear inscripción</h1>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<form action="<?= base_url('inscripciones/create') ?>" method="post" class="row g-3">
    <div class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Alumno</label>
            <select name="alumno_id" class="form-select" required>
                <option value="">Seleccione un alumno</option>
                <?php foreach ($alumnos as $a): ?>
                    <option value="<?= esc($a['id']) ?>" <?= (string) old('alumno_id') === (string) $a['id'] ? 'selected' : '' ?>>
                        <?= esc($a['codigo'] . ' - ' . $a['apellido'] . ' ' . $a['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Ciclo</label>
            <?php $selectedCiclo = old('ciclo_id'); ?>
            <select name="ciclo_id" class="form-select">
                <option value="">(Seleccione ciclo)</option>
                <?php if (! empty($ciclos)): ?>
                    <?php foreach ($ciclos as $c): ?>
                        <option value="<?= esc($c['id_ciclo']) ?>" <?= (string) $selectedCiclo === (string) $c['id_ciclo'] ? 'selected' : '' ?>>
                            <?= esc($c['nombre'] . (isset($c['activo']) && $c['activo'] ? ' (activo)' : '')) ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <label class="form-label">Horario</label>
        <div class="form-text mb-2">Selecciona hasta 5 horarios (cada uno será una inscripción).</div>
        <div class="list-group horarios-list" style="max-height:300px;overflow:auto;">
            <?php foreach ($horarios as $h): ?>
                <label class="list-group-item">
                    <input type="checkbox" name="horario_id[]" value="<?= esc($h['id']) ?>" class="form-check-input me-2 horario-checkbox" <?= in_array((string)$h['id'], (array) old('horario_id', [])) ? 'checked' : '' ?>>
                    <?= esc($h['nombre_docente'] . ' | ' . $h['nombre_materia'] . ' | ' . $h['dia'] . ' ' . $h['hora_inicio'] . '-' . $h['hora_fin']) ?>
                </label>
            <?php endforeach; ?>
        </div>
        <div class="form-text" id="horario-help"></div>
    </div>

    <div class="col-12 mt-3">
        <button type="submit" class="btn btn-success">Guardar</button>
        <a href="<?= base_url('inscripciones') ?>" class="btn btn-secondary ms-2">Cancelar</a>
    </div>
</form>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkboxes = document.querySelectorAll('.horario-checkbox');

    // información devuelta por la API: cntDistinct (materias distintas actuales) y remaining (cupo para nuevas)
    let apiInfo = {cntDistinct: 0, remaining: 5, horarios: []};

    function applyExisting(horarios) {
        // primero limpiar marcas previas
        document.querySelectorAll('.horario-checkbox').forEach(cb => {
            cb.disabled = false;
            const lbl = cb.closest('label');
            lbl.classList.remove('list-group-item-secondary');
            const badge = lbl.querySelector('.existing-badge');
            if (badge) badge.remove();
        });

        horarios.forEach(id => {
            const cb = document.querySelector('.horario-checkbox[value="' + id + '"]');
            if (cb) {
                cb.checked = true;
                cb.disabled = true;
                const lbl = cb.closest('label');
                lbl.classList.add('list-group-item-secondary');
                const span = document.createElement('span');
                span.className = 'badge bg-secondary ms-2 existing-badge';
                span.textContent = 'inscripto';
                lbl.appendChild(span);
            }
        });
    }

    function updateCount() {
        const help = document.getElementById('horario-help');
        const newSelected = document.querySelectorAll('.horario-checkbox:checked:not([disabled])').length;
        const existing = apiInfo.cntDistinct || 0;
        const remaining = apiInfo.remaining || 0;
        const left = Math.max(0, remaining - newSelected);
        help.textContent = existing + ' asignada(s). ' + newSelected + ' nueva(s) seleccionada(s). Cupo restante: ' + left;
        return {newSelected, existing, remaining};
    }

    function enforceLimitOnChange(cb) {
        cb.addEventListener('change', function () {
            const newSelected = document.querySelectorAll('.horario-checkbox:checked:not([disabled])').length;
            if (newSelected > apiInfo.remaining) {
                this.checked = false;
                return alert('No puedes agregar más materias: límite por ciclo alcanzado.');
            }
            updateCount();
        });
    }

    checkboxes.forEach(enforceLimitOnChange);

    async function fetchAlumnoInscripciones(alumnoId, cicloId) {
        if (!alumnoId) {
            apiInfo = {cntDistinct:0, remaining:5, horarios:[]};
            applyExisting([]);
            updateCount();
            return;
        }
        try {
            const url = '<?= base_url('inscripciones/api/alumno/') ?>' + alumnoId + (cicloId ? '?ciclo_id=' + cicloId : '');
            const res = await fetch(url, {headers: {'X-Requested-With': 'XMLHttpRequest'}});
            const data = await res.json();
            apiInfo = data;
            applyExisting(data.horarios || []);
            updateCount();
        } catch (e) {
            console.error(e);
        }
    }

    // disparadores: cambio de alumno o ciclo
    const alumnoSelect = document.querySelector('select[name="alumno_id"]');
    const cicloSelect = document.querySelector('select[name="ciclo_id"]');
    alumnoSelect.addEventListener('change', function () {
        fetchAlumnoInscripciones(this.value, cicloSelect ? cicloSelect.value : null);
    });
    if (cicloSelect) cicloSelect.addEventListener('change', function () {
        fetchAlumnoInscripciones(alumnoSelect.value, this.value);
    });

    // carga inicial si hay alumno seleccionado (por old value)
    if (alumnoSelect.value) {
        fetchAlumnoInscripciones(alumnoSelect.value, cicloSelect ? cicloSelect.value : null);
    } else {
        updateCount();
    }
});
</script>
<?= $this->endSection() ?>