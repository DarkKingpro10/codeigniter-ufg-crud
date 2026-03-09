<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\MateriaModel;
use App\Models\InscripcionModel;
use App\Models\NotaModel;

class Notas extends BaseController
{
    public function index()
    {
        $materiaModel = new MateriaModel();
        $db = \Config\Database::connect();
        $ciclos = $db->table('ciclos')->orderBy('activo', 'DESC')->get()->getResultArray();

        // Periodos disponibles por ciclo (4 periodos)
        $periodos = [
            'Lab1',
            'Parcial1',
            'Lab2',
            'Parcial2',
            'Lab3',
            'Parcial3',
            'LabFinal',
            'ParcialFinal',
        ];

        // Si vienen parámetros por GET, redirigir a la ruta de edición
        $gMateria = $this->request->getGet('materia_id');
        $gCiclo = $this->request->getGet('ciclo_id');
        $gPeriodo = $this->request->getGet('periodo');
        if ($gMateria && $gCiclo && $gPeriodo) {
            return redirect()->to(base_url('notas/edit/' . (int)$gMateria . '/' . (int)$gCiclo . '/' . rawurlencode($gPeriodo)));
        }

        return view('notas/index', [
            'materias' => $materiaModel->orderBy('nombre_materia', 'ASC')->findAll(),
            'ciclos' => $ciclos,
            'periodos' => $periodos,
        ]);
    }

    public function edit(int $materiaId, int $cicloId, string $periodo): string
    {
        $inscripcionModel = new InscripcionModel();
        $notaModel = new NotaModel();

        $alumnos = $inscripcionModel->obtenerAlumnosPorMateria($materiaId, $cicloId);
        $notas = $notaModel->getByMateriaCicloPeriodo($materiaId, $cicloId, $periodo);

        // map notas por alumno_id
        $mapNotas = [];
        foreach ($notas as $n) {
            $mapNotas[$n['alumno_id']] = $n;
        }

        return view('notas/edit', [
            'materiaId' => $materiaId,
            'cicloId' => $cicloId,
            'periodo' => $periodo,
            'alumnos' => $alumnos,
            'notasMap' => $mapNotas,
        ]);
    }

    public function save()
    {
        $materiaId = (int) $this->request->getPost('materia_id');
        $cicloId = (int) $this->request->getPost('ciclo_id');
        $periodo = (string) $this->request->getPost('periodo');

        $notas = $this->request->getPost('nota'); // array alumno_id => nota
        $obs = $this->request->getPost('observaciones');

        $data = [];
        if (is_array($notas)) {
            foreach ($notas as $alumnoId => $valor) {
                $data[$alumnoId] = [
                    'nota' => $valor,
                    'observaciones' => is_array($obs) && isset($obs[$alumnoId]) ? $obs[$alumnoId] : null,
                ];
            }
        }

        $notaModel = new NotaModel();
        $notaModel->guardarMasivo($materiaId, $cicloId, $periodo, $data);

        return redirect()->to(base_url('notas'))->with('success', 'Notas guardadas.');
    }
}
