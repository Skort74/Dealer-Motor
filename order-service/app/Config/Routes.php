<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'OrderWebController::index');
$routes->get('/orders', 'OrderWebController::index');
$routes->get('/orders/create', 'OrderWebController::create');
$routes->post('/orders/store', 'OrderWebController::store');
$routes->get('/orders/edit/(:num)', 'OrderWebController::edit/$1');
$routes->post('/orders/update/(:num)', 'OrderWebController::update/$1');
$routes->post('/orders/cancel/(:num)', 'OrderWebController::cancel/$1');
$routes->get('/orders/payment/(:num)', 'OrderWebController::payment/$1');
$routes->post('/orders/pay/(:num)', 'OrderWebController::pay/$1');

$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    $routes->get('orders', 'OrderController::index');
    $routes->post('orders', 'OrderController::store');
    $routes->get('orders/statistics', 'OrderController::statistics');
    $routes->get('orders/(:num)', 'OrderController::show/$1');
    $routes->put('orders/(:num)', 'OrderController::update/$1');
    $routes->delete('orders/(:num)', 'OrderController::cancel/$1');
    $routes->post('orders/(:num)/pay', 'OrderController::pay/$1');
    $routes->get('health', 'HealthController::index');
});
