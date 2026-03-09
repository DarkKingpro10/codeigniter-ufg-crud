<?php

declare(strict_types=1);

namespace App\Models;

use CodeIgniter\Model;

class NotaModel extends Model
{
    protected $table            = 'notas';
    protected $primaryKey       = 'id_nota';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';

    protected $allowedFields = [
        'alumno_id',
        'materia_id',
        'ciclo_id',
        'periodo',
        'nota',
        'observaciones',
    ];

    public function getByMateriaCicloPeriodo(int $materiaId, int $cicloId, string $periodo): array
    {
        return $this->where('materia_id', $materiaId)
            ->where('ciclo_id', $cicloId)
            ->where('periodo', $periodo)
            ->findAll();
    }

    /**
     * Guardado masivo: $data = [ alumno_id => ['nota' => x, 'observaciones' => y], ... ]
     */
    public function guardarMasivo(int $materiaId, int $cicloId, string $periodo, array $data): void
    {
        foreach ($data as $alumnoId => $vals) {
            $alumnoId = (int) $alumnoId;

            $row = $this->where(['alumno_id' => $alumnoId, 'materia_id' => $materiaId, 'ciclo_id' => $cicloId, 'periodo' => $periodo])->first();

            $record = [
                'alumno_id' => $alumnoId,
                'materia_id' => $materiaId,
                'ciclo_id' => $cicloId,
                'periodo' => $periodo,
                'nota' => isset($vals['nota']) && $vals['nota'] !== '' ? $vals['nota'] : null,
                'observaciones' => $vals['observaciones'] ?? null,
            ];

            if ($row) {
                $this->update((int)$row[$this->primaryKey], $record);
            } else {
                $this->insert($record);
            }
        }
    }
}
