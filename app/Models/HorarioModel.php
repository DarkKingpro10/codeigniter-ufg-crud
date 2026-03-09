<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class HorarioModel extends Model
{
    protected $table            = 'horarios';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'id_docente',
        'id_materia',
        'dia',
        'hora_inicio',
        'hora_fin',
    ];

    public function contarPorDocente(int $idDocente): int
    {
        return (int) $this->where('id_docente', $idDocente)->countAllResults();
    }

    public function existeHorario(
        int $idDocente,
        int $idMateria,
        string $dia,
        string $horaInicio,
        string $horaFin
    ): bool {
        return (bool) $this
            ->where('id_docente', $idDocente)
            ->where('id_materia', $idMateria)
            ->where('dia', $dia)
            ->where('hora_inicio', $horaInicio)
            ->where('hora_fin', $horaFin)
            ->first();
    }

    /**
     * Comprueba si existe un horario idéntico para un docente, opcionalmente excluyendo un id
     */
    public function existeHorarioExcepto(
        int $idDocente,
        int $idMateria,
        string $dia,
        string $horaInicio,
        string $horaFin,
        ?int $excludeId = null
    ): bool {
        $builder = $this->where('id_docente', $idDocente)
            ->where('id_materia', $idMateria)
            ->where('dia', $dia)
            ->where('hora_inicio', $horaInicio)
            ->where('hora_fin', $horaFin);

        if ($excludeId !== null && $excludeId > 0) {
            $builder->where('id <>', $excludeId);
        }

        return (bool) $builder->first();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    /**
     * @param int|null $idDocente Filtrar por id de docente o null para todos
     * @return array<int, array<string, mixed>>
     */
    public function listarConDetalles(?int $idDocente = null): array
    {
        $builder = $this->db->table($this->table . ' h')
            ->select('h.id, h.id_docente, d.nombre_docente, h.id_materia, m.nombre_materia, h.dia, h.hora_inicio, h.hora_fin')
            ->join('docentes d', 'd.id_docente = h.id_docente')
            ->join('materias m', 'm.id_materia = h.id_materia');

        if ($idDocente !== null && $idDocente > 0) {
            $builder->where('h.id_docente', $idDocente);
        }

        return $builder
            ->orderBy('d.nombre_docente', 'ASC')
            ->orderBy('m.nombre_materia', 'ASC')
            ->orderBy('h.dia', 'ASC')
            ->orderBy('h.hora_inicio', 'ASC')
            ->get()
            ->getResultArray();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function listarOpciones(): array
    {
        return $this->db->table($this->table . ' h')
            ->select('h.id, d.nombre_docente, m.nombre_materia, h.dia, h.hora_inicio, h.hora_fin')
            ->join('docentes d', 'd.id_docente = h.id_docente')
            ->join('materias m', 'm.id_materia = h.id_materia')
            ->orderBy('d.nombre_docente', 'ASC')
            ->orderBy('m.nombre_materia', 'ASC')
            ->orderBy('h.dia', 'ASC')
            ->orderBy('h.hora_inicio', 'ASC')
            ->get()
            ->getResultArray();
    }
}
