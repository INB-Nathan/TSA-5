<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Login::index');
$routes->post('/login/authenticate', 'Login::authenticate');
$routes->get('/coffee', 'Coffee::index', ['filter' => 'auth']);
$routes->get('/register', 'Register::index');
$routes->post('/register/create', 'Register::create');
