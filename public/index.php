<?php
require __DIR__ . '/../app/core/Router.php';

$router = new Router();

$router->GET('/', ['HomeController', 'index']);

$router->GET('/login',  ['AuthController', 'showlogin']);
$router->POST('/login', ['AuthController', 'login']);
$router->GET('/register', ['AuthController', 'showregister']);


$router->dispatch($_SERVER['REQUEST_URI']);





















