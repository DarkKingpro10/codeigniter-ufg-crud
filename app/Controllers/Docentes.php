<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\DocenteModel;
use CodeIgniter\HTTP\RedirectResponse;

class Docentes extends BaseController
{
    public function index(): string
    {
        $model = new DocenteModel();

        return view('docentes/index', [
            'docentes' => $model->orderBy('nombre_docente', 'ASC')->findAll(),
        ]);
    }

    public function renderCreate(): string
    {
        return view('docentes/create');
    }

    public function create(): RedirectResponse
    {
        $nombre = trim((string) $this->request->getPost('nombre_docente'));

        if ($nombre === '') {
            return redirect()->back()->withInput()->with('error', 'El nombre del docente es obligatorio.');
        }

        $model = new DocenteModel();

        if (! $model->insert(['nombre_docente' => $nombre])) {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear el docente.');
        }

        return redirect()->to(base_url('docentes'))->with('success', 'Docente creado correctamente.');
    }

    public function renderEdit(int $idDocente): string
    {
        $model = new DocenteModel();
        $docente = $model->find($idDocente);

        if ($docente === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Docente no encontrado.');
        }

        return view('docentes/edit', [
            'docente' => $docente,
        ]);
    }

    public function edit(int $idDocente): RedirectResponse
    {
        $nombre = trim((string) $this->request->getPost('nombre_docente'));

        if ($nombre === '') {
            return redirect()->back()->withInput()->with('error', 'El nombre del docente es obligatorio.');
        }

        $model = new DocenteModel();

        if (! $model->update($idDocente, ['nombre_docente' => $nombre])) {
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar el docente.');
        }

        return redirect()->to(base_url('docentes'))->with('success', 'Docente actualizado correctamente.');
    }
}
