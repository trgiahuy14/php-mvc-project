<?php

require_once './configs/config.php';
require_once './configs/database.php';
require_once './app/Models/CoreModel.php';
require_once './app/Models/Users.php';
require_once './app/Models/Groups.php';

require_once './app/Controllers/BaseController.php';
require_once './app/Controllers/UsersController.php';

$controller = new UsersController();

$controller->index();
