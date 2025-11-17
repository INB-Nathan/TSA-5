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
$routes->get('/logout', 'Login::logout');

// Password reset routes (no auth required - public access)
// GET route redirects to login page since form is embedded there
$routes->get('/request-password-reset', function() {
    return redirect()->to('/');
});
$routes->post('/request-password-reset', 'Account::requestPasswordReset');
$routes->get('/reset-password/(:segment)', 'Account::resetPassword/$1');
$routes->post('/reset-password', 'Account::resetPassword');

// Admin routes
$routes->group('admin', ['filter' => 'admin'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('users', 'Admin::users');
    $routes->get('users/edit/(:num)', 'Admin::editUser/$1');
    $routes->post('users/update/(:num)', 'Admin::updateUser/$1');
    $routes->get('users/delete/(:num)', 'Admin::deleteUser/$1');
    $routes->get('items', 'Admin::items');
    $routes->post('items/create', 'Admin::createItem');
    $routes->get('items/delete/(:num)', 'Admin::deleteItem/$1');
    $routes->get('announcements', 'Admin::announcements');
    $routes->post('announcements/create', 'Admin::createAnnouncement');
    $routes->get('announcements/delete/(:num)', 'Admin::deleteAnnouncement/$1');
});

// Account routes (authenticated users)
$routes->group('account', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Account::index');
    $routes->post('change-password', 'Account::changePassword');
    $routes->post('delete', 'Account::deleteAccount');
});
