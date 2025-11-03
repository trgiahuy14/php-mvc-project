<?php

$router->get('/dashboard', 'UsersController@dashboard');

$router->get('/users', 'UsersController@index');
$router->post('/users', 'UsersController@index');

$router->get('/group', 'GroupController@index');
$router->post('/group', 'GroupController@index');

$router->get('/product', 'ProductController@index');

$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');

$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');


// echo '<pre>';
// print_r($router->getRoute());
// echo '</pre>';
