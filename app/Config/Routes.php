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