<?php

function dd($value){
    echo "<pre>";
    var_dump($value);
    echo "</pre>";

    die();
}

function app($name) {
    global $c;

    return $c->get($name);
}

function config($name) {
    return app('config')[$name];
}

function request() {
    return app('request');
}

function session() {
    return app('session');
}

function auth() {
    return app('user');
}

function db() {
    return app('db');
}

function view($name, $data = []) {

    return app('view')->render($name, $data);
}

function base_url() {

    echo $_SERVER['REQUEST_URI'] . 'public';
}