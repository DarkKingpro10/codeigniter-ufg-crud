<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');

$routes->get('alumnos', 'Alumnos::index');
$routes->get('alumnos/create', 'Alumnos::renderCreate');
$routes->post('alumnos/create', 'Alumnos::create');
$routes->get('alumnos/edit/(:num)', 'Alumnos::renderEdit/$1');
$routes->post('alumnos/edit/(:num)', 'Alumnos::edit/$1');
$routes->delete('alumnos/delete/(:num)', 'Alumnos::delete/$1');
$routes->get('alumnos_carrera', 'Alumnoscarrera::index');
$routes->post('alumnos_carrera/filtrar', 'Alumnoscarrera::filtrar');

// Carreras
$routes->get('carreras', 'Carreras::index');
$routes->get('carreras/create', 'Carreras::renderCreate');
$routes->post('carreras/create', 'Carreras::create');
$routes->get('carreras/edit/(:num)', 'Carreras::renderEdit/$1');
$routes->post('carreras/edit/(:num)', 'Carreras::edit/$1');

// Materias
$routes->get('materias', 'Materias::index');
$routes->get('materias/create', 'Materias::renderCreate');
$routes->post('materias/create', 'Materias::create');
$routes->get('materias/edit/(:num)', 'Materias::renderEdit/$1');
$routes->post('materias/edit/(:num)', 'Materias::edit/$1');

// Docentes (profesores)
$routes->get('docentes', 'Docentes::index');
$routes->get('docentes/create', 'Docentes::renderCreate');
$routes->post('docentes/create', 'Docentes::create');
$routes->get('docentes/edit/(:num)', 'Docentes::renderEdit/$1');
$routes->post('docentes/edit/(:num)', 'Docentes::edit/$1');

// Horarios (asignación de materias por docente, máx 5)
$routes->get('horarios', 'Horarios::index');
$routes->get('horarios/asignar', 'Horarios::renderAsignar');
$routes->post('horarios/asignar', 'Horarios::asignar');

// Inscripciones (alumnos a horarios)
$routes->get('inscripciones', 'Inscripciones::index');
$routes->get('inscripciones/create', 'Inscripciones::renderCreate');
$routes->post('inscripciones/create', 'Inscripciones::create');
$routes->get('inscripciones/edit/(:num)', 'Inscripciones::renderEdit/$1');
$routes->post('inscripciones/edit/(:num)', 'Inscripciones::edit/$1');
$routes->delete('inscripciones/delete/(:num)', 'Inscripciones::delete/$1');

// Reporte: alumnos por materia
$routes->get('alumnos_materia', 'AlumnosMateria::index');
$routes->post('alumnos_materia/filtrar', 'AlumnosMateria::filtrar');
