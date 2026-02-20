<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\InscripcionModel;
use App\Models\MateriaModel;
use CodeIgniter\HTTP\RedirectResponse;

class AlumnosMateria extends BaseController
{
    public function index(): string
    {
        $materiaModel = new MateriaModel();

        return view('alumnos_materia/index', [
            'materias' => $materiaModel->orderBy('nombre_materia', 'ASC')->findAll(),
            'alumnos' => [],
            'id_materia' => null,
        ]);
    }

    public function filtrar(): string
    {
        $idMateria = (int) $this->request->getPost('id_materia');

        $materiaModel = new MateriaModel();
        $inscripcionModel = new InscripcionModel();

        return view('alumnos_materia/index', [
            'materias' => $materiaModel->orderBy('nombre_materia', 'ASC')->findAll(),
            'alumnos' => $idMateria > 0 ? $inscripcionModel->obtenerAlumnosPorMateria($idMateria) : [],
            'id_materia' => $idMateria > 0 ? $idMateria : null,
        ]);
    }
}
