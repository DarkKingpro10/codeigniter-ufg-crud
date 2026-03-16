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
        $horarioInput = $this->request->getPost('horario_id');
        // permitir enviar un único horario o un array de horarios
        $horarioIds = [];
        if (is_array($horarioInput)) {
            $horarioIds = array_map('intval', $horarioInput);
        } else {
            $h = (int) $horarioInput;
            if ($h > 0) {
                $horarioIds = [$h];
            }
        }
        $cicloId = $this->request->getPost('ciclo_id') ? (int)$this->request->getPost('ciclo_id') : null;

        if ($alumnoId <= 0 || empty($horarioIds)) {
            return redirect()->back()->withInput()->with('error', 'Debes seleccionar alumno y al menos un horario.');
        }

        if (count($horarioIds) > 5) {
            return redirect()->back()->withInput()->with('error', 'Puedes seleccionar como máximo 5 horarios.');
        }

        $model = new InscripcionModel();

        // obtener materias de los horarios seleccionados y validar
        $materiasNuevas = [];
        foreach ($horarioIds as $hid) {
            $idMat = $model->obtenerMateriaPorHorario($hid);
            if ($idMat === null) {
                return redirect()->back()->withInput()->with('error', 'Horario inválido.');
            }
            $materiasNuevas[] = $idMat;
        }
        $materiasNuevas = array_values(array_unique($materiasNuevas));

        // si no nos pasaron ciclo_id, intentar obtener el ciclo activo
        if ($cicloId === null) {
            $db = \Config\Database::connect();
            $c = $db->table('ciclos')->where('activo', 1)->get()->getRowArray();
            $cicloId = $c ? (int)$c['id_ciclo'] : null;
        }
        // contar materias distintas ya inscriptas
        $cntActual = $model->contarMateriasDistintasPorAlumno($alumnoId, $cicloId);
        $aAgregar = 0;
        foreach ($materiasNuevas as $mat) {
            if (! $model->alumnoTieneMateria($alumnoId, $mat, $cicloId)) {
                $aAgregar++;
            }
        }
        if ($cntActual + $aAgregar > 5) {
            return redirect()->back()->withInput()->with('error', 'El alumno excedería las 5 materias en este ciclo. Ajuste la selección.');
        }

        // insertar varias inscripciones en transacción
        $db = \Config\Database::connect();
        $db->transStart();
        foreach ($horarioIds as $hid) {
            if ($model->existeInscripcion($alumnoId, $hid)) {
                // ya existe, saltar
                continue;
            }

            $data = ['alumno_id' => $alumnoId, 'horario_id' => $hid];
            if ($cicloId !== null) {
                $data['ciclo_id'] = $cicloId;
            }

            $model->insert($data);
        }
        $db->transComplete();

        if (! $db->transStatus()) {
            return redirect()->back()->withInput()->with('error', 'No se pudieron crear las inscripciones.');
        }

        return redirect()->to(base_url('inscripciones'))->with('success', 'Inscripciones creadas correctamente.');
    }

    /**
     * Mostrar y editar inscripciones de un alumno (gestión por alumno)
     */
    public function renderAlumno(int $alumnoId): string
    {
        $alumnoModel = new AlumnoModel();
        $horarioModel = new HorarioModel();
        $model = new InscripcionModel();

        $alumno = $alumnoModel->find($alumnoId);
        if ($alumno === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Alumno no encontrado.');
        }

        $db = \Config\Database::connect();
        $ciclos = $db->table('ciclos')->orderBy('activo', 'DESC')->get()->getResultArray();

        // ciclo activo por defecto
        $cicloId = $this->request->getGet('ciclo_id') ? (int)$this->request->getGet('ciclo_id') : null;
        if ($cicloId === null) {
            $c = $db->table('ciclos')->where('activo', 1)->get()->getRowArray();
            $cicloId = $c ? (int)$c['id_ciclo'] : null;
        }

        // inscripciones actuales del alumno en ese ciclo
        $inscripciones = $db->table('incripcion i')
            ->select('i.*')
            ->where('i.alumno_id', $alumnoId)
            ->where('i.ciclo_id', $cicloId)
            ->get()
            ->getResultArray();

        return view('inscripciones/manage_alumno', [
            'alumno' => $alumno,
            'horarios' => $horarioModel->listarOpciones(),
            'ciclos' => $ciclos,
            'ciclo_id' => $cicloId,
            'inscripciones' => $inscripciones,
        ]);
    }

    public function updateAlumno(int $alumnoId)
    {
        $alumnoModel = new AlumnoModel();
        $model = new InscripcionModel();

        $alumno = $alumnoModel->find($alumnoId);
        if ($alumno === null) {
            return redirect()->back()->with('error', 'Alumno no encontrado.');
        }

        $cicloId = $this->request->getPost('ciclo_id') ? (int)$this->request->getPost('ciclo_id') : null;
        $horarioInput = $this->request->getPost('horario_id');
        $horarioIds = [];
        if (is_array($horarioInput)) {
            $horarioIds = array_map('intval', $horarioInput);
        } else {
            $h = (int) $horarioInput;
            if ($h > 0) $horarioIds = [$h];
        }

        if (count($horarioIds) > 5) {
            return redirect()->back()->withInput()->with('error', 'Máximo 5 horarios.');
        }

        // validar materias y límites
        $materias = [];
        foreach ($horarioIds as $hid) {
            $m = $model->obtenerMateriaPorHorario($hid);
            if ($m === null) return redirect()->back()->withInput()->with('error', 'Horario inválido.');
            $materias[] = $m;
        }
        $materias = array_values(array_unique($materias));

        $cntActual = $model->contarMateriasDistintasPorAlumno($alumnoId, $cicloId);
        // las materias seleccionadas pueden incluir materias que ya tenía; contamos solo nuevas
        $aAgregar = 0;
        foreach ($materias as $mat) {
            if (! $model->alumnoTieneMateria($alumnoId, $mat, $cicloId)) $aAgregar++;
        }
        if ($cntActual + $aAgregar > 5) {
            return redirect()->back()->withInput()->with('error', 'El alumno excedería las 5 materias en este ciclo.');
        }

        // reemplazar inscripciones del alumno en el ciclo: borrar y volver a insertar
        $db = \Config\Database::connect();
        $db->transStart();
        $db->table('incripcion')->where('alumno_id', $alumnoId)->where('ciclo_id', $cicloId)->delete();
        foreach ($horarioIds as $hid) {
            $data = ['alumno_id' => $alumnoId, 'horario_id' => $hid];
            if ($cicloId !== null) $data['ciclo_id'] = $cicloId;
            $model->insert($data);
        }
        $db->transComplete();
        if (! $db->transStatus()) return redirect()->back()->with('error', 'No se pudieron actualizar las inscripciones.');

        return redirect()->to(base_url('inscripciones'))->with('success', 'Inscripciones actualizadas.');
    }

    /**
     * API: devolver horarios ya inscriptos (por alumno y ciclo)
     */
    public function apiAlumno(int $alumnoId)
    {
        $cicloId = $this->request->getGet('ciclo_id') ? (int)$this->request->getGet('ciclo_id') : null;
        $db = \Config\Database::connect();
        if ($cicloId === null) {
            $c = $db->table('ciclos')->where('activo', 1)->get()->getRowArray();
            $cicloId = $c ? (int)$c['id_ciclo'] : null;
        }

        $horarios = [];
        if ($cicloId !== null) {
            $rows = $db->table('incripcion')->select('horario_id')->where('alumno_id', $alumnoId)->where('ciclo_id', $cicloId)->get()->getResultArray();
            foreach ($rows as $r) $horarios[] = (int) $r['horario_id'];
        }

        $model = new InscripcionModel();
        $cnt = $model->contarMateriasDistintasPorAlumno($alumnoId, $cicloId);
        $remaining = max(0, 5 - $cnt);

        return $this->response->setJSON(['horarios' => $horarios, 'cntDistinct' => $cnt, 'remaining' => $remaining]);
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
