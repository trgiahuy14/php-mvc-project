<?php

// Require PHP files from folder Configs
foreach (glob(__DIR__ . '/configs/*.php') as $filename) {
    require_once $filename;
}

// Require PHP files from folder core
foreach (glob(__DIR__ . '/core/*.php') as $filename) {
    require_once $filename;
}

// Require PHP files from folder Models
foreach (glob(__DIR__ . '/app/Models/*.php') as $filename) {
    require_once $filename;
}

// Require PHP files from folder Controllers
foreach (glob(__DIR__ . '/app/Controllers/*.php') as $filename) {
    require_once $filename;
}

$controller = new UsersController();

$controller->index();
