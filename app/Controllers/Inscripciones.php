<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\AlumnoModel;
use App\Models\HorarioModel;
use App\Models\InscripcionModel;
use CodeIgniter\HTTP\RedirectResponse;

class Inscripciones extends BaseController
{
    public function index(): string
    {
        $model = new InscripcionModel();

        return view('inscripciones/index', [
            'inscripciones' => $model->listarConDetalles(),
        ]);
    }

    public function renderCreate(): string
    {
        $alumnoModel = new AlumnoModel();
        $horarioModel = new HorarioModel();

        $db = \Config\Database::connect();
        $ciclos = $db->table('ciclos')->orderBy('activo', 'DESC')->get()->getResultArray();

        return view('inscripciones/create', [
            'alumnos' => $alumnoModel->orderBy('apellido', 'ASC')->orderBy('nombre', 'ASC')->findAll(),
            'horarios' => $horarioModel->listarOpciones(),
            'ciclos' => $ciclos,
        ]);
    }

    public function create(): RedirectResponse
    {
        $alumnoId = (int) $this->request->getPost('alumno_id');
        $horarioId = (int) $this->request->getPost('horario_id');
        $cicloId = $this->request->getPost('ciclo_id') ? (int)$this->request->getPost('ciclo_id') : null;

        if ($alumnoId <= 0 || $horarioId <= 0) {
            return redirect()->back()->withInput()->with('error', 'Debes seleccionar alumno y horario.');
        }

        $model = new InscripcionModel();

        if ($model->existeInscripcion($alumnoId, $horarioId)) {
            return redirect()->back()->withInput()->with('error', 'Este alumno ya está inscrito en ese horario.');
        }

        // obtener materia del horario y validar límite por ciclo
        $idMateria = $model->obtenerMateriaPorHorario($horarioId);
        if ($idMateria === null) {
            return redirect()->back()->withInput()->with('error', 'Horario inválido.');
        }

        // si no nos pasaron ciclo_id, intentar obtener el ciclo activo
        if ($cicloId === null) {
            $db = \Config\Database::connect();
            $c = $db->table('ciclos')->where('activo', 1)->get()->getRowArray();
            $cicloId = $c ? (int)$c['id_ciclo'] : null;
        }

        if (! $model->alumnoTieneMateria($alumnoId, $idMateria, $cicloId)) {
            $cnt = $model->contarMateriasDistintasPorAlumno($alumnoId, $cicloId);
            if ($cnt >= 5) {
                return redirect()->back()->withInput()->with('error', 'El alumno ya tiene 5 materias en este ciclo. Elimine una antes de agregar otra.');
            }
        }

        $data = ['alumno_id' => $alumnoId, 'horario_id' => $horarioId];
        if ($cicloId !== null) {
            $data['ciclo_id'] = $cicloId;
        }

        if (! $model->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear la inscripción.');
        }

        return redirect()->to(base_url('inscripciones'))->with('success', 'Inscripción creada correctamente.');
    }

    public function renderEdit(int $idInscripcion): string
    {
        $model = new InscripcionModel();
        $inscripcion = $model->find($idInscripcion);

        if ($inscripcion === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Inscripción no encontrada.');
        }

        $alumnoModel = new AlumnoModel();
        $horarioModel = new HorarioModel();

        $db = \Config\Database::connect();
        $ciclos = $db->table('ciclos')->orderBy('activo', 'DESC')->get()->getResultArray();

        return view('inscripciones/edit', [
            'inscripcion' => $inscripcion,
            'alumnos' => $alumnoModel->orderBy('apellido', 'ASC')->orderBy('nombre', 'ASC')->findAll(),
            'horarios' => $horarioModel->listarOpciones(),
            'ciclos' => $ciclos,
        ]);
    }

    public function edit(int $idInscripcion): RedirectResponse
    {
        $alumnoId = (int) $this->request->getPost('alumno_id');
        $horarioId = (int) $this->request->getPost('horario_id');
        $cicloId = $this->request->getPost('ciclo_id') ? (int)$this->request->getPost('ciclo_id') : null;

        if ($alumnoId <= 0 || $horarioId <= 0) {
            return redirect()->back()->withInput()->with('error', 'Debes seleccionar alumno y horario.');
        }

        $model = new InscripcionModel();

        if ($model->existeInscripcion($alumnoId, $horarioId, $idInscripcion)) {
            return redirect()->back()->withInput()->with('error', 'Este alumno ya está inscrito en ese horario.');
        }

        $idMateria = $model->obtenerMateriaPorHorario($horarioId);
        if ($idMateria === null) {
            return redirect()->back()->withInput()->with('error', 'Horario inválido.');
        }

        if ($cicloId === null) {
            $db = \Config\Database::connect();
            $c = $db->table('ciclos')->where('activo', 1)->get()->getRowArray();
            $cicloId = $c ? (int)$c['id_ciclo'] : null;
        }

        if (! $model->alumnoTieneMateria($alumnoId, $idMateria, $cicloId, $idInscripcion)) {
            $cnt = $model->contarMateriasDistintasPorAlumno($alumnoId, $cicloId, $idInscripcion);
            if ($cnt >= 5) {
                return redirect()->back()->withInput()->with('error', 'El alumno ya tiene 5 materias en este ciclo. Elimine una antes de agregar otra.');
            }
        }

        $data = ['alumno_id' => $alumnoId, 'horario_id' => $horarioId];
        if ($cicloId !== null) {
            $data['ciclo_id'] = $cicloId;
        }

        if (! $model->update($idInscripcion, $data)) {
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar la inscripción.');
        }

        return redirect()->to(base_url('inscripciones'))->with('success', 'Inscripción actualizada correctamente.');
    }

    public function delete(int $idInscripcion)
    {
        $model = new InscripcionModel();
        $model->delete($idInscripcion);

        return $this->response->setJSON(['status' => 'ok']);
    }
}
