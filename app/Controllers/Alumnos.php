<?php

namespace App\Controllers;

use App\Models\AlumnoModel;
use App\Models\CarreraModel;

class Alumnos extends BaseController
{

    protected $alumnoModel;

    public function __construct()
    {
        $this->alumnoModel = new AlumnoModel();
    }

    public function index()
    {
        // $alumnoModel = new AlumnoModel();

        $data['alumnos'] = $this->alumnoModel->findAll();
        return view('alumnos/index', $data);
    }

    public function renderCreate()
    {
        $carreraModel = new CarreraModel();

        return view('alumnos/create', [
            'carreras' => $carreraModel->orderBy('nombre_carrera', 'ASC')->findAll(),
        ]);
    }

    public function create()
    {
        $post = $this->request->getPost();
        $codigo = isset($post['codigo']) ? trim($post['codigo']) : null;
        $codigoCarrera = isset($post['codigo_carrera']) ? trim((string) $post['codigo_carrera']) : null;

        if (empty($codigo)) {
            return redirect()->back()->withInput()->with('error', 'El código es obligatorio.');
        }

        if (empty($codigoCarrera) || ! ctype_digit((string) $codigoCarrera) || (int) $codigoCarrera <= 0) {
            return redirect()->back()->withInput()->with('error', 'Debes seleccionar una carrera válida.');
        }

        $existing = $this->alumnoModel->where('codigo', $codigo)->first();
        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'Ya existe un alumno con ese código.');
        }

        try {
            $this->alumnoModel->insert($post);
            return redirect()->to('alumnos')->with('success', 'Alumno creado correctamente.');
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al guardar el alumno.');
        }
    }

    public function renderEdit($id)
    {
        $carreraModel = new CarreraModel();

        return view('alumnos/edit', [
            'alumno' => $this->alumnoModel->find($id),
            'carreras' => $carreraModel->orderBy('nombre_carrera', 'ASC')->findAll(),
        ]);
    }

    public function edit($id)
    {
        $data['alumno'] = $this->alumnoModel->find($id);
        $post = $this->request->getPost();
        $codigo = isset($post['codigo']) ? trim($post['codigo']) : null;
        $codigoCarrera = isset($post['codigo_carrera']) ? trim((string) $post['codigo_carrera']) : null;

        if (empty($codigo)) {
            return redirect()->back()->withInput()->with('error', 'El código es obligatorio.');
        }

        if (empty($codigoCarrera) || ! ctype_digit((string) $codigoCarrera) || (int) $codigoCarrera <= 0) {
            return redirect()->back()->withInput()->with('error', 'Debes seleccionar una carrera válida.');
        }

        $existing = $this->alumnoModel->where('codigo', $codigo)->first();
        if ($existing && $existing['id'] != $id) {
            return redirect()->back()->withInput()->with('error', 'Ya existe un alumno con ese código.');
        }

        try {
            $this->alumnoModel->update($id, $post);
            return redirect()->to('alumnos')->with('success', 'Alumno actualizado correctamente.');
        } catch (\Exception $e) {
            log_message('error', $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Ocurrió un error al actualizar el alumno.');
        }
    }

    public function delete($id)
    {
        $this->alumnoModel->delete($id);
        return $this->response->setJSON([
            'status' => 'ok'
        ]);
    }
}
