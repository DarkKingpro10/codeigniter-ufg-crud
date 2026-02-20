<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\DocenteModel;
use App\Models\HorarioModel;
use App\Models\MateriaModel;
use CodeIgniter\HTTP\RedirectResponse;

class Horarios extends BaseController
{
    private const MAX_MATERIAS_POR_DOCENTE = 5;

    public function index(): string
    {
        $horarioModel = new HorarioModel();

        return view('horarios/index', [
            'horarios' => $horarioModel->listarConDetalles(),
        ]);
    }

    public function renderAsignar(): string
    {
        $docenteModel = new DocenteModel();
        $materiaModel = new MateriaModel();

        return view('horarios/asignar', [
            'docentes' => $docenteModel->orderBy('nombre_docente', 'ASC')->findAll(),
            'materias' => $materiaModel->orderBy('nombre_materia', 'ASC')->findAll(),
            'maxMaterias' => self::MAX_MATERIAS_POR_DOCENTE,
        ]);
    }

    public function asignar(): RedirectResponse
    {
        $idDocente = (int) $this->request->getPost('id_docente');

        if ($idDocente <= 0) {
            return redirect()->back()->withInput()->with('error', 'Debes seleccionar un docente.');
        }

        $materias = (array) $this->request->getPost('id_materia');
        $dias = (array) $this->request->getPost('dia');
        $horasInicio = (array) $this->request->getPost('hora_inicio');
        $horasFin = (array) $this->request->getPost('hora_fin');

        $horarioModel = new HorarioModel();
        $actuales = $horarioModel->contarPorDocente($idDocente);

        $nuevos = [];

        for ($i = 0; $i < self::MAX_MATERIAS_POR_DOCENTE; $i++) {
            $idMateria = isset($materias[$i]) ? (int) $materias[$i] : 0;
            $dia = isset($dias[$i]) ? trim((string) $dias[$i]) : '';
            $horaInicio = isset($horasInicio[$i]) ? trim((string) $horasInicio[$i]) : '';
            $horaFin = isset($horasFin[$i]) ? trim((string) $horasFin[$i]) : '';

            $filaVacia = ($idMateria === 0 && $dia === '' && $horaInicio === '' && $horaFin === '');
            if ($filaVacia) {
                continue;
            }

            if ($idMateria <= 0 || $dia === '' || $horaInicio === '' || $horaFin === '') {
                return redirect()->back()->withInput()->with('error', 'Completa todos los campos de cada materia seleccionada.');
            }

            if ($horaInicio >= $horaFin) {
                return redirect()->back()->withInput()->with('error', 'La hora de inicio debe ser menor que la hora fin.');
            }

            if ($horarioModel->existeHorario($idDocente, $idMateria, $dia, $horaInicio, $horaFin)) {
                return redirect()->back()->withInput()->with('error', 'Ya existe un horario idéntico para este docente.');
            }

            $nuevos[] = [
                'id_docente' => $idDocente,
                'id_materia' => $idMateria,
                'dia' => $dia,
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
            ];
        }

        if (count($nuevos) === 0) {
            return redirect()->back()->withInput()->with('error', 'No seleccionaste ninguna materia para asignar.');
        }

        if (($actuales + count($nuevos)) > self::MAX_MATERIAS_POR_DOCENTE) {
            $restantes = max(0, self::MAX_MATERIAS_POR_DOCENTE - $actuales);
            return redirect()->back()->withInput()->with('error', 'Este docente ya tiene ' . $actuales . ' materia(s). Solo puedes agregar ' . $restantes . ' más.');
        }

        foreach ($nuevos as $row) {
            if (! $horarioModel->insert($row)) {
                return redirect()->back()->withInput()->with('error', 'No se pudieron guardar los horarios.');
            }
        }

        return redirect()->to(base_url('horarios'))->with('success', 'Horarios asignados correctamente.');
    }
}
