<?php

namespace App\Models;
use CodeIgniter\Model;

class Alumno_carreraModel extends Model
{
    protected $table = 'alumnos';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nombre','apellido','telefono','codigo_carrera'];

    // Obtener todas las carreras
    public function obtenerCarreras()
    {
        return $this->db->table('carrera')->get()->getResult();
    }

    // Obtener alumnos por carrera
    public function obtenerAlumnosPorCarrera($codigo_carrera)
    {
        return $this->db->table('alumnos a')
                ->select('a.*, c.nombre_carrera')
                ->join('carrera c', 'c.codigo_carrera = a.codigo_carrera')
                ->where('a.codigo_carrera', $codigo_carrera)
                ->get()
                ->getResult();
    }
}

?>