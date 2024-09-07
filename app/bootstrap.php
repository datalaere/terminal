<?php

session_start();

define('PATH_ROOT', dirname(__DIR__));

require PATH_ROOT . '/vendor/autoload.php';


$app = new \Slim\App(require __DIR__ . '/settings.php');

require PATH_ROOT . '/app/config/container.php';

require PATH_ROOT . '/app/config/env.php';

require PATH_ROOT . '/app/config/services.php';

require PATH_ROOT . '/app/config/database.php';

require PATH_ROOT . '/app/config/view.php';

require PATH_ROOT . '/app/config/controllers.php';

require PATH_ROOT . '/app/routes.php';