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

// Dashboard 
$router->get('/dashboard', 'UsersController@dashboard');

// Posts
$router->get('/posts', 'PostsController@list');

$router->get('/posts/add', 'PostsController@showAdd');
$router->post('/posts/add', 'PostsController@add');

$router->get('/posts/edit', 'PostsController@showEdit');
$router->post('/posts/edit', 'PostsController@edit');

$router->get('/posts/delete', 'PostsController@delete');

// Client Home
$router->get('/', 'HomeController@index');


// Debug
// echo '<pre>';
// print_r($router->getRoute());
// echo '</pre>';
