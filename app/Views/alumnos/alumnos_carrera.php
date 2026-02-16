<!DOCTYPE html>
<html>
<head>
    <title>Alumnos por Carrera</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listado de Alumnos por carrera</title>
    <!--Bootstrap CSS-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <!--  Datatables  -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.css"/>  
    <!--  Datatables Responsive  -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.2.3/css/responsive.dataTables.min.css">

    <style>
        thead{
            background-color: rgb(16, 105, 179);
            color: #fff;
        }
    </style>
</head>

<body>


<div class="col-sm-12">

<h1 class="text-center">Listado de Alumnos por carrera</h1>
<h2>Filtrar alumnos por carrera</h2>
<hr class="border border-primary border-3 opacity-75">
    
<div class="container">
        
<div class="row">
<div class="col-md-12">
<table id="registros" class="table table-border table-hover" cellspacing="0" width="100%">

 <div class="card">
            <div class="card-header">

<h3>Seleccione una carrera...

<form method="post" action="<?= base_url('alumnos_carrera/filtrar') ?>">

<div class="input-group">
  <select name="codigo_carrera" class="custom-select" id="inputGroupSelect04" required>
    <option selected>Seleccione una carrera...</option>
    <?php foreach($carreras as $c): ?>
    <option value="<?= $c->codigo_carrera ?>">
                <?= $c->codigo_carrera ?> - <?= $c->nombre_carrera ?>
            </option>
    <?php endforeach; ?>       
  </select>
  <div class="input-group-append">
    <button class="btn btn-secondary" type="submit">Buscar</button></div> 

    <a href="<?= base_url('alumnos/') ?>" class="btn btn-danger float-end">Salir</a>

</div>
    
</h3>

</form>

<hr>

<thead>
    <tr>
        <th>Id</th>
        <th>Nombres</th>
        <th>Apellidos</th>
        <th>Teléfonos</th>
        <th>Carrera</th>
    </tr>
    </thead>
    
    <tbody>

    <?php if(!empty($alumnos)): ?>
        <?php foreach($alumnos as $a): ?>
        <tr>
            <td><?= $a->id ?></td>
            <td><?= $a->nombre ?></td>
            <td><?= $a->apellido ?></td>
            <td><?= $a->telefono ?></td>
            <td><?= $a->nombre_carrera ?></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
    <?php else: ?>
    <p>No se encontraron resultados.</p>
    <?php endif; ?>


    </div>
    </div>

    </div>


<!--Bootstrap JS-->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/js/bootstrap.min.js" integrity="sha384-VHvPCCyXqtD5DqJeNxl2dtTyhF78xXNXdkwX1CZeRusQfRKp+tA7hAShOK/B/fQ2" crossorigin="anonymous"></script>
    <!-- Datatables-->
    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.20/datatables.min.js"></script>  
    <!-- Datatables responsive -->
    <script src="https://cdn.datatables.net/responsive/2.2.3/js/dataTables.responsive.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#registros').DataTable({
                responsive: true
            });
        });
    </script>

</div>
</div>

</body>
</html>