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

        $idDocente = (int) $this->request->getGet('id_docente');

        $docenteModel = new DocenteModel();

        return view('horarios/index', [
            'horarios' => $horarioModel->listarConDetalles($idDocente > 0 ? $idDocente : null),
            'docentes' => $docenteModel->orderBy('nombre_docente', 'ASC')->findAll(),
            'selectedDocente' => $idDocente,
        ]);
    }

    public function renderAsignar(): string
    {
        $docenteModel = new DocenteModel();
        $materiaModel = new MateriaModel();

        // Cargar todos los horarios y agruparlos por id_docente para la vista
        $horarioModel = new HorarioModel();
        $todosHorarios = $horarioModel->listarConDetalles(null);
        $horariosPorDocente = [];
        foreach ($todosHorarios as $h) {
            $idDoc = (int) ($h['id_docente'] ?? 0);
            if ($idDoc <= 0) {
                continue;
            }
            if (! isset($horariosPorDocente[$idDoc])) {
                $horariosPorDocente[$idDoc] = [];
            }
            $horariosPorDocente[$idDoc][] = [
                'id' => $h['id'] ?? null,
                'id_materia' => $h['id_materia'] ?? null,
                'dia' => $h['dia'] ?? '',
                'hora_inicio' => $h['hora_inicio'] ?? '',
                'hora_fin' => $h['hora_fin'] ?? '',
            ];
        }

        return view('horarios/asignar', [
            'docentes' => $docenteModel->orderBy('nombre_docente', 'ASC')->findAll(),
            'materias' => $materiaModel->orderBy('nombre_materia', 'ASC')->findAll(),
            'maxMaterias' => self::MAX_MATERIAS_POR_DOCENTE,
            'horariosPorDocente' => $horariosPorDocente,
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
        $horarioIds = (array) $this->request->getPost('horario_id');

        $horarioModel = new HorarioModel();
        $actuales = $horarioModel->contarPorDocente($idDocente);

        // Primero calcular cuántas eliminaciones e inserciones habría
        $eliminaciones = 0;
        $inserciones = 0;
        for ($i = 0; $i < self::MAX_MATERIAS_POR_DOCENTE; $i++) {
            $idHora = isset($horarioIds[$i]) ? (int) $horarioIds[$i] : 0;
            $idMateria = isset($materias[$i]) ? (int) $materias[$i] : 0;
            $dia = isset($dias[$i]) ? trim((string) $dias[$i]) : '';
            $horaInicio = isset($horasInicio[$i]) ? trim((string) $horasInicio[$i]) : '';
            $horaFin = isset($horasFin[$i]) ? trim((string) $horasFin[$i]) : '';

            $filaVacia = ($idMateria === 0 && $dia === '' && $horaInicio === '' && $horaFin === '');

            if ($idHora > 0 && $filaVacia) {
                $eliminaciones++;
            }

            if ($idHora === 0 && ! $filaVacia) {
                $inserciones++;
            }
        }

        if (($actuales - $eliminaciones + $inserciones) > self::MAX_MATERIAS_POR_DOCENTE) {
            $restantes = max(0, self::MAX_MATERIAS_POR_DOCENTE - ($actuales - $eliminaciones));
            return redirect()->back()->withInput()->with('error', 'Este docente ya tiene ' . $actuales . ' materia(s). Solo puedes agregar ' . $restantes . ' más.');
        }

        // Ejecutar operaciones: eliminar, actualizar o insertar según corresponda
        for ($i = 0; $i < self::MAX_MATERIAS_POR_DOCENTE; $i++) {
            $idHora = isset($horarioIds[$i]) ? (int) $horarioIds[$i] : 0;
            $idMateria = isset($materias[$i]) ? (int) $materias[$i] : 0;
            $dia = isset($dias[$i]) ? trim((string) $dias[$i]) : '';
            $horaInicio = isset($horasInicio[$i]) ? trim((string) $horasInicio[$i]) : '';
            $horaFin = isset($horasFin[$i]) ? trim((string) $horasFin[$i]) : '';

            $filaVacia = ($idMateria === 0 && $dia === '' && $horaInicio === '' && $horaFin === '');

            if ($idHora > 0 && $filaVacia) {
                // eliminar
                $horarioModel->delete($idHora);
                continue;
            }

            if ($idMateria <= 0 || $dia === '' || $horaInicio === '' || $horaFin === '') {
                if ($idHora === 0 && $filaVacia) {
                    // fila vacía nueva, ignorar
                    continue;
                }
                return redirect()->back()->withInput()->with('error', 'Completa todos los campos de cada materia seleccionada.');
            }

            if ($horaInicio >= $horaFin) {
                return redirect()->back()->withInput()->with('error', 'La hora de inicio debe ser menor que la hora fin.');
            }

            // comprobar duplicados, excluyendo el id actual si actualizamos
            if ($horarioModel->existeHorarioExcepto($idDocente, $idMateria, $dia, $horaInicio, $horaFin, $idHora > 0 ? $idHora : null)) {
                return redirect()->back()->withInput()->with('error', 'Ya existe un horario idéntico para este docente.');
            }

            $row = [
                'id_docente' => $idDocente,
                'id_materia' => $idMateria,
                'dia' => $dia,
                'hora_inicio' => $horaInicio,
                'hora_fin' => $horaFin,
            ];

            if ($idHora > 0) {
                // actualizar
                if (! $horarioModel->update($idHora, $row)) {
                    return redirect()->back()->withInput()->with('error', 'No se pudieron actualizar los horarios.');
                }
            } else {
                // insertar
                if (! $horarioModel->insert($row)) {
                    return redirect()->back()->withInput()->with('error', 'No se pudieron guardar los horarios.');
                }
            }
        }

        return redirect()->to(base_url('horarios'))->with('success', 'Horarios guardados correctamente.');
    }
}
