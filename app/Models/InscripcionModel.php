<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class InscripcionModel extends Model
{
    protected $table            = 'incripcion';
    protected $primaryKey       = 'id_inscripcion';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'horario_id',
        'alumno_id',
    ];

    public function existeInscripcion(int $alumnoId, int $horarioId, ?int $excluirId = null): bool
    {
        $builder = $this->where('alumno_id', $alumnoId)->where('horario_id', $horarioId);

        if ($excluirId !== null) {
            $builder->where($this->primaryKey . ' !=', $excluirId);
        }

        return (bool) $builder->first();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listarConDetalles(): array
    {
        return $this->db->table($this->table . ' i')
            ->select('i.id_inscripcion, i.horario_id, i.alumno_id, a.codigo, a.nombre, a.apellido, m.nombre_materia, d.nombre_docente, h.dia, h.hora_inicio, h.hora_fin')
            ->join('alumnos a', 'a.id = i.alumno_id')
            ->join('horarios h', 'h.id = i.horario_id')
            ->join('materias m', 'm.id_materia = h.id_materia')
            ->join('docentes d', 'd.id_docente = h.id_docente')
            ->orderBy('m.nombre_materia', 'ASC')
            ->orderBy('d.nombre_docente', 'ASC')
            ->orderBy('h.dia', 'ASC')
            ->orderBy('h.hora_inicio', 'ASC')
            ->orderBy('a.apellido', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function obtenerAlumnosPorMateria(int $idMateria): array
    {
        return $this->db->table($this->table . ' i')
            ->select('a.id, a.codigo, a.nombre, a.apellido, a.telefono')
            ->join('alumnos a', 'a.id = i.alumno_id')
            ->join('horarios h', 'h.id = i.horario_id')
            ->where('h.id_materia', $idMateria)
            ->groupBy('a.id')
            ->orderBy('a.apellido', 'ASC')
            ->get()
            ->getResultArray();
    }
}
