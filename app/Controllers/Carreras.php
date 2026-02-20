<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Models\CarreraModel;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;

class Carreras extends BaseController
{
    public function index(): string
    {
        $model = new CarreraModel();

        return view('carreras/index', [
            'carreras' => $model->orderBy('nombre_carrera', 'ASC')->findAll(),
        ]);
    }

    public function renderCreate(): string
    {
        return view('carreras/create');
    }

    public function create(): RedirectResponse
    {
        $nombreCarrera = trim((string) $this->request->getPost('nombre_carrera'));
        $codigoCarrera = trim((string) $this->request->getPost('codigo_carrera'));

        if ($nombreCarrera === '') {
            return redirect()->back()->withInput()->with('error', 'El nombre de la carrera es obligatorio.');
        }

        $data = [
            'nombre_carrera' => $nombreCarrera,
        ];

        if ($codigoCarrera !== '') {
            if (! ctype_digit($codigoCarrera)) {
                return redirect()->back()->withInput()->with('error', 'El código de carrera debe ser numérico.');
            }

            $data['codigo_carrera'] = (int) $codigoCarrera;
        }

        $model = new CarreraModel();

        if (! $model->insert($data)) {
            return redirect()->back()->withInput()->with('error', 'No se pudo crear la carrera.');
        }

        return redirect()->to(base_url('carreras'))->with('success', 'Carrera creada correctamente.');
    }

    public function renderEdit(int $codigoCarrera): string
    {
        $model = new CarreraModel();
        $carrera = $model->find($codigoCarrera);

        if ($carrera === null) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Carrera no encontrada.');
        }

        return view('carreras/edit', [
            'carrera' => $carrera,
        ]);
    }

    public function edit(int $codigoCarrera): RedirectResponse
    {
        $nombreCarrera = trim((string) $this->request->getPost('nombre_carrera'));

        if ($nombreCarrera === '') {
            return redirect()->back()->withInput()->with('error', 'El nombre de la carrera es obligatorio.');
        }

        $model = new CarreraModel();

        if (! $model->update($codigoCarrera, ['nombre_carrera' => $nombreCarrera])) {
            return redirect()->back()->withInput()->with('error', 'No se pudo actualizar la carrera.');
        }

        return redirect()->to(base_url('carreras'))->with('success', 'Carrera actualizada correctamente.');
    }
}
