<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\MateriaModel;
use CodeIgniter\HTTP\RedirectResponse;

class Materias extends BaseController
{
    public function index(): string
    {
        $model = new MateriaModel();

        return view('materias/index', [
            'materias' => $model->orderBy('nombre_materia', 'ASC')->findAll(),
        ]);
    }

    public function renderCreate(): string
    {
        return view('materias/create');
    }

    public function create(): RedirectResponse
    {
        $nombreMateria = trim((string) $this->request->getPost('nombre_materia'));

        if ($nombreMateria === '') {
            return redirect()->back()->withInput()->with('error', 'El nombre de la materia es obligatorio.');
        }

        $model = new MateriaModel();

        if (! $model->insert(['nombre_materia' => $nombreMateria])) {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear la materia.');
        }

        return redirect()->to(base_url('materias'))->with('success', 'Materia creada correctamente.');
    }

    public function renderEdit(int $idMateria): string
    {
        $model = new MateriaModel();
        $materia = $model->find($idMateria);

        if ($materia === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Materia no encontrada.');
        }

        return view('materias/edit', [
            'materia' => $materia,
        ]);
    }

    public function edit(int $idMateria): RedirectResponse
    {
        $nombreMateria = trim((string) $this->request->getPost('nombre_materia'));

        if ($nombreMateria === '') {
            return redirect()->back()->withInput()->with('error', 'El nombre de la materia es obligatorio.');
        }

        $model = new MateriaModel();

        if (! $model->update($idMateria, ['nombre_materia' => $nombreMateria])) {
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar la materia.');
        }

        return redirect()->to(base_url('materias'))->with('success', 'Materia actualizada correctamente.');
    }
}
