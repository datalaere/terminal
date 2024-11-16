<?php

use App\Host\HostController;
use App\Host\Debug\DebugController;
use App\Host\HostService as Host;
use App\User\UserService as User;

$app->get('/host-create', [HostController::class, 'create']);

if(User::auth()) {
    $app->get('/login', [HostController::class, 'logon']);
    $app->get('/scan', [HostController::class, 'scan']);
    $app->get('/connect', [HostController::class, 'connect']);
    $app->get('/telnet', [HostController::class, 'connect']);
}

if(Host::guest()) {
    $app->get('/logon', [HostController::class, 'logon']);
    $app->get('/dump', [DebugController::class, 'dump']);
    $app->get('/mem', [DebugController::class, 'dump']);
    $app->get('/set', [DebugController::class, 'set']);
    $app->get('/run', [DebugController::class, 'run']);  
    
    // Sysadmin
    $app->get('/sysadmin571_bypass', [HostController::class, 'sysadmin']);
}

if(Host::auth()) {
    // Host
    $app->get('/echo', [HostController::class, 'echo']);
    $app->get('/dir', [HostController::class, 'dir']);
    $app->get('/ls', [HostController::class, 'dir']);
    $app->get('/more', [HostController::class, 'open']);
    $app->get('/open', [HostController::class, 'open']);
    $app->get('/echo', [HostController::class, 'echo']);
    $app->get('/logoff', [HostController::class, 'logoff']);
    $app->get('/exit', [HostController::class, 'logoff']);
    
}
