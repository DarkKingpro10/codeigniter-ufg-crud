<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.datatables.net/2.3.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" integrity="sha384-q6bAgUAsga3oT16XWJ1toXdKcHmBp45jM5roe3RCQ6dET9xGL89Qmpx4tJAI2pm2" crossorigin="anonymous">
<link href="https://cdn.datatables.net/responsive/3.0.8/css/responsive.bootstrap5.min.css" rel="stylesheet" integrity="sha384-seyUnB//1QOFEqox9uI7YTLBgz9jBwFRqZvsEPFrTw6NAsFEo70nhBWsQfODqiYA" crossorigin="anonymous">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Horarios (Materias por docente)</h1>
    <a href="<?= base_url('horarios/asignar') ?>" class="btn btn-primary">Asignar materias</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table id="horarios_table" class="table table-striped table-bordered align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Docente</th>
                <th>Materia</th>
                <th>Día</th>
                <th>Hora inicio</th>
                <th>Hora fin</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($horarios as $h): ?>
                <tr>
                    <td><?= esc($h['id']) ?></td>
                    <td><?= esc($h['nombre_docente']) ?></td>
                    <td><?= esc($h['nombre_materia']) ?></td>
                    <td><?= esc($h['dia']) ?></td>
                    <td><?= esc($h['hora_inicio']) ?></td>
                    <td><?= esc($h['hora_fin']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha384-NXgwF8Kv9SSAr+jemKKcbvQsz+teULH/a5UNJvZc6kP47hZgl62M1vGnw6gHQhb1" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.min.js" integrity="sha384-Lo2E+ASlyar6zUXt6sPEy5uaDGtDGHXo300rohaW4/AV26JdoLBT9zhcOhV8BtL8" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/2.3.7/js/dataTables.bootstrap5.min.js" integrity="sha384-3BApNGXgbm9rg2kjIbaEVprAGb2B0n9QyLjBrH090WdkzZ3IiUv8RZoTh5uP8oWH" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/3.0.8/js/dataTables.responsive.min.js" integrity="sha384-jp1JS4vMvRmld4ZK9Co4AZftrm1tt3FbEZrCdFZIcoRiazQGkTqOS1QqI060oG2F" crossorigin="anonymous"></script>
<script src="https://cdn.datatables.net/responsive/3.0.8/js/responsive.bootstrap5.js" integrity="sha384-hyp/YDWGBMFqg7pJuS+y+2VWJkwnOyX+oMN9fWcxINo2flqjC/SdNaHj8LIV4zKJ" crossorigin="anonymous"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        new DataTable('#horarios_table', {
            responsive: true,
            language: {
                emptyTable: 'No hay horarios registrados.'
            }
        })
    });
</script>
<?= $this->endSection() ?>
