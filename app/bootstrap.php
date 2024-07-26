<?php

// load application config (error reporting etc.)
require  ROOT . 'app/config/app.php';

// auto-loading the classes (currently only from application/libs) via Composer's PSR-4 auto-loader
// later it might be useful to use a namespace here, but for now let's keep it as simple as possible
require ROOT . 'vendor/autoload.php';

DB::connect(DB_HOST, DB_NAME, DB_USER, DB_PASS);

require_once APP_MODEL . 'server.php';

Session::init(); // Start the session

require_once APP_CONTROLLER . 'system.php';
require_once APP_CONTROLLER . 'debug.php';
require_once APP_CONTROLLER . 'filesystem.php';
require_once APP_CONTROLLER . 'auth.php';
require_once APP_CONTROLLER . 'server.php';
require_once APP_CONTROLLER . 'info.php';
require_once APP . 'helpers.php';