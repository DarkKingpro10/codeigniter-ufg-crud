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

        return view('inscripciones/create', [
            'alumnos' => $alumnoModel->orderBy('apellido', 'ASC')->orderBy('nombre', 'ASC')->findAll(),
            'horarios' => $horarioModel->listarOpciones(),
        ]);
    }

    public function create(): RedirectResponse
    {
        $alumnoId = (int) $this->request->getPost('alumno_id');
        $horarioId = (int) $this->request->getPost('horario_id');

        if ($alumnoId <= 0 || $horarioId <= 0) {
            return redirect()->back()->withInput()->with('error', 'Debes seleccionar alumno y horario.');
        }

        $model = new InscripcionModel();

        if ($model->existeInscripcion($alumnoId, $horarioId)) {
            return redirect()->back()->withInput()->with('error', 'Este alumno ya está inscrito en ese horario.');
        }

        if (! $model->insert(['alumno_id' => $alumnoId, 'horario_id' => $horarioId])) {
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

        return view('inscripciones/edit', [
            'inscripcion' => $inscripcion,
            'alumnos' => $alumnoModel->orderBy('apellido', 'ASC')->orderBy('nombre', 'ASC')->findAll(),
            'horarios' => $horarioModel->listarOpciones(),
        ]);
    }

    public function edit(int $idInscripcion): RedirectResponse
    {
        $alumnoId = (int) $this->request->getPost('alumno_id');
        $horarioId = (int) $this->request->getPost('horario_id');

        if ($alumnoId <= 0 || $horarioId <= 0) {
            return redirect()->back()->withInput()->with('error', 'Debes seleccionar alumno y horario.');
        }

        $model = new InscripcionModel();

        if ($model->existeInscripcion($alumnoId, $horarioId, $idInscripcion)) {
            return redirect()->back()->withInput()->with('error', 'Este alumno ya está inscrito en ese horario.');
        }

        if (! $model->update($idInscripcion, ['alumno_id' => $alumnoId, 'horario_id' => $horarioId])) {
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
