<?= $this->extend('layouts/main') ?>

<?= $this->section('styles') ?>
<link href="https://cdn.datatables.net/2.3.7/css/dataTables.bootstrap5.min.css" rel="stylesheet" integrity="sha384-q6bAgUAsga3oT16XWJ1toXdKcHmBp45jM5roe3RCQ6dET9xGL89Qmpx4tJAI2pm2" crossorigin="anonymous">
<link href="https://cdn.datatables.net/responsive/3.0.8/css/responsive.bootstrap5.min.css" rel="stylesheet" integrity="sha384-seyUnB//1QOFEqox9uI7YTLBgz9jBwFRqZvsEPFrTw6NAsFEo70nhBWsQfODqiYA" crossorigin="anonymous">
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3">Docentes</h1>
    <a href="<?= base_url('docentes/create') ?>" class="btn btn-primary">Crear docente</a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success"><?= esc(session()->getFlashdata('success')) ?></div>
<?php endif; ?>
<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger"><?= esc(session()->getFlashdata('error')) ?></div>
<?php endif; ?>

<div class="table-responsive">
    <table id="docentes_table" class="table table-striped table-bordered align-middle text-center">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($docentes as $d): ?>
                <tr>
                    <td><?= esc($d['id_docente']) ?></td>
                    <td><?= esc($d['nombre_docente']) ?></td>
                    <td>
                        <a href="<?= base_url('docentes/edit/' . $d['id_docente']) ?>" class="btn btn-sm btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="auto" data-bs-trigger="hover focus" data-bs-content="Editar docente">
                            <i class="bi bi-pencil"></i>
                        </a>
                    </td>
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
        new DataTable('#docentes_table', {
            responsive: true,
            language: {
                emptyTable: 'No hay docentes registrados.'
            }
        })

        const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]');
        [...popoverTriggerList].map(el => new bootstrap.Popover(el))
    });
</script>
<?= $this->endSection() ?>