<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class DocenteModel extends Model
{
    protected $table            = 'docentes';
    protected $primaryKey       = 'id_docente';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'nombre_docente',
    ];
}
