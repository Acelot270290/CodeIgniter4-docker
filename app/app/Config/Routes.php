<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'UsersController::index');
$routes->get('users', 'UsersController::index');
$routes->post('users/getUsers', 'UsersController::getUsers');
$routes->post('users/create', 'UsersController::create');
$routes->post('users/update/(:num)', 'UsersController::update/$1');
$routes->delete('users/delete/(:num)', 'UsersController::delete/$1');
