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
        'ciclo_id',
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
    public function obtenerAlumnosPorMateria(int $idMateria, ?int $cicloId = null): array
    {
        $builder = $this->db->table($this->table . ' i')
            ->select('a.id, a.codigo, a.nombre, a.apellido, a.telefono')
            ->join('alumnos a', 'a.id = i.alumno_id')
            ->join('horarios h', 'h.id = i.horario_id')
            ->where('h.id_materia', $idMateria);

        if ($cicloId !== null) {
            $builder->where('i.ciclo_id', $cicloId);
        }

        return $builder->groupBy('a.id')
            ->orderBy('a.apellido', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * Devuelve el id_materia dado un horario
     */
    public function obtenerMateriaPorHorario(int $horarioId): ?int
    {
        $row = $this->db->table('horarios')
            ->select('id_materia')
            ->where('id', $horarioId)
            ->get()
            ->getRowArray();

        return $row ? (int) $row['id_materia'] : null;
    }

    /**
     * Cuenta las materias distintas de un alumno dentro de un ciclo (opcional).
     * Excluir una inscripcion por su id si se necesita (para editar).
     */
    public function contarMateriasDistintasPorAlumno(int $alumnoId, ?int $cicloId = null, ?int $excluirInscripcionId = null): int
    {
        $sql = "SELECT COUNT(DISTINCT h.id_materia) AS cnt
            FROM {$this->table} i
            JOIN horarios h ON h.id = i.horario_id
            WHERE i.alumno_id = ?";

        $params = [$alumnoId];

        if ($cicloId !== null) {
            $sql .= " AND i.ciclo_id = ?";
            $params[] = $cicloId;
        }

        if ($excluirInscripcionId !== null) {
            $sql .= " AND i.{$this->primaryKey} != ?";
            $params[] = $excluirInscripcionId;
        }

        $row = $this->db->query($sql, $params)->getRow();
        return $row ? (int) $row->cnt : 0;
    }

    /**
     * Comprueba si un alumno ya tiene una materia (en el mismo ciclo opcionalmente).
     */
    public function alumnoTieneMateria(int $alumnoId, int $idMateria, ?int $cicloId = null, ?int $excluirInscripcionId = null): bool
    {
        $sql = "SELECT 1
            FROM {$this->table} i
            JOIN horarios h ON h.id = i.horario_id
            WHERE i.alumno_id = ? AND h.id_materia = ?";

        $params = [$alumnoId, $idMateria];

        if ($cicloId !== null) {
            $sql .= " AND i.ciclo_id = ?";
            $params[] = $cicloId;
        }

        if ($excluirInscripcionId !== null) {
            $sql .= " AND i.{$this->primaryKey} != ?";
            $params[] = $excluirInscripcionId;
        }

        $row = $this->db->query($sql, $params)->getRow();
        return $row ? true : false;
    }
}
