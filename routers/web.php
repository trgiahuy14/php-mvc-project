<?php

$router->get('/users', 'UsersController@index');
$router->post('/users', 'UsersController@index');

$router->get('/group', 'GroupController@index');
$router->post('/group', 'GroupController@index');

$router->get('/product', 'ProductController@index');

// echo "file router web";
// echo '<pre>';
// print_r($router->getRoute());
// echo '</pre>';
