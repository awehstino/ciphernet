<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// $routes->get('/users', 'Signup::users');
// $routes->post('/sign', 'Signup::register');
// $routes->post('/login', 'Login::authenticate');





// User Management
$routes->get('/users', 'Signup::users');
$routes->post('/sign', 'Signup::register');
$routes->post('/login', 'Login::authenticate');

// Project Management

    $routes->get('/projects', 'Projects::getProjects');
    $routes->get('/pending', 'Projects::getPending');
    $routes->get('/inprogress', 'Projects::getInprogress');
    $routes->get('/completed', 'Projects::getCompleted');
    $routes->get('/project/(:segment)', 'Projects::getProject/$1');
    $routes->post('/newproject', 'Projects::createProject');
    $routes->patch('/update/(:segment)', 'Projects::update/$1');
    $routes->post('/comment', 'Projects::addComment');


// Admin Routes
// $routes->get('/create_project', 'Projects::adminProject', );
$routes->get('/signin_login', 'Projects::adminsignin', );

$routes->post('/admin_login', 'AdminAuth::login', );  
$routes->get('/admin_logout', 'AdminAuth::logout');  
$routes->post('/admin_signin', 'AdminAuth::register', );  
// $routes->get('/admin_dashboard', 'AdminAuth::dashboard', );


$routes->group('', ['filter' => 'adminAuth'], function($routes) {
    $routes->get('/create_project', 'Projects::adminProject');
    $routes->get('/', 'AdminAuth::dashboard');
    $routes->get('/admin_dashboard', 'AdminAuth::dashboard');
    $routes->get('/coreupdates', 'AdminAuth::coreupdate');
    $routes->get('/otherupdates', 'AdminAuth::otherupdate');

    // other admin routes
});



