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
        $this->alumnoModel->insert($this->request->getPost());
        return redirect()->to('alumnos');
    }

    public function renderEdit($id)
    {
        $data['alumno'] = $this->alumnoModel->find($id);
        return view('alumnos/edit', $data);
    }

    public function edit($id)
    {
        $data['alumno'] = $this->alumnoModel->find($id);
        $this->alumnoModel->update($id, $this->request->getPost());
        return redirect()->to('alumnos');
    }

    public function delete($id)
    {
        $this->alumnoModel->delete($id);
        return $this->response->setJSON([
            'status' => 'ok'
        ]);
    }
}
