<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('api/logout', 'UserController::logout');
$routes->get('test-database-connection', 'UserController::testDatabaseConnection');
$routes->get('api/check-auth', 'UserController::checkAuth');
$routes->get('api/products', 'Products::index');
$routes->get('api/products/edit/(:num)', 'Products::edit/$1');
$routes->put('api/products/update/(:num)', 'Products::update/$1');
$routes->delete('api/products/delete/(:num)', 'Products::delete/$1');
$routes->post('api/products/create', 'Products::create');
$routes->get('api/categories', 'Products::categories');
$routes->post('user/register', 'OAuthController::register');
$routes->post('user/login', 'OAuthController::login');



