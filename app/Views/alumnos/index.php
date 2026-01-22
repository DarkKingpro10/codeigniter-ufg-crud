<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Alumnos</h1>
    <a href="<?= base_url('alumnos/create') ?>" class="btn btn-primary">Crear alumno</a>
</div>

<div class="table-responsive">
    <table class="table table-striped table-bordered align-middle">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Teléfono</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($alumnos)): ?>
                <tr>
                    <td colspan="5" class="text-center">No hay alumnos registrados.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($alumnos as $alumno): ?>
                    <tr>
                        <td><?= esc($alumno['id']) ?></td>
                        <td><?= esc($alumno['nombre']) ?></td>
                        <td><?= esc($alumno['apellido']) ?></td>
                        <td><?= esc($alumno['telefono']) ?></td>
                        <td>
                            <a href="<?= base_url('alumnos/edit/' . $alumno['id']) ?>" class="btn btn-sm btn-secondary">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>