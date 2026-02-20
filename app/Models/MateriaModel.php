<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class MateriaModel extends Model
{
    protected $table            = 'materias';
    protected $primaryKey       = 'id_materia';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'nombre_materia',
    ];
}
