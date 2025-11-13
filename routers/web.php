<?php

// Authentication
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');

$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');

$router->get('/forgot', 'AuthController@showForgot');
$router->post('/forgot', 'AuthController@forgot');

$router->get('/active', 'AuthController@active');

$router->get('/reset', 'AuthController@showReset');
$router->post('/reset', 'AuthController@reset');

$router->get('/logout', 'AuthController@logout');

// Dashboard 
$router->get('/dashboard', 'UserController@dashboard');

// Posts
$router->get('/posts', 'PostController@list');

$router->get('/posts/add', 'PostController@showAdd');
$router->post('/posts/add', 'PostController@add');

$router->get('/posts/edit', 'PostController@showEdit');
$router->post('/posts/edit', 'PostController@edit');

$router->get('/posts/delete', 'PostController@delete');

// Client Home
$router->get('/', 'HomeController@index');

// For debug
// echo '<pre>';
// print_r($router->getRoute());
// echo '</pre>';
