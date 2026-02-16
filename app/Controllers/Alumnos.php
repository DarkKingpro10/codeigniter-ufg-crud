<?php

namespace App\Controllers;

use App\Models\AlumnoModel;

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
        return view('alumnos/create');
    }

    public function create()
    {
        $post = $this->request->getPost();
        $codigo = isset($post['codigo']) ? trim($post['codigo']) : null;

        if (empty($codigo)) {
            return redirect()->back()->withInput()->with('error', 'El código es obligatorio.');
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
        $data['alumno'] = $this->alumnoModel->find($id);
        return view('alumnos/edit', $data);
    }

    public function edit($id)
    {
        $data['alumno'] = $this->alumnoModel->find($id);
        $post = $this->request->getPost();
        $codigo = isset($post['codigo']) ? trim($post['codigo']) : null;

        if (empty($codigo)) {
            return redirect()->back()->withInput()->with('error', 'El código es obligatorio.');
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
