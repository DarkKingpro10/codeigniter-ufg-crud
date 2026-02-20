<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class CarreraModel extends Model
{
    protected $table            = 'carrera';
    protected $primaryKey       = 'codigo_carrera';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'codigo_carrera',
        'nombre_carrera',
    ];
}
