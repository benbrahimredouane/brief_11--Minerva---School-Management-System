<?php
session_start();
require __DIR__ . '/../app/core/Router.php';

$router = new Router();

$router->GET('/', ['HomeController', 'index']);

$router->GET('/login',  ['AuthController', 'showlogin']);
$router->POST('/login', ['AuthController', 'login']);
$router->GET('/register', ['AuthController', 'showregister']);
$router->POST('/register', ['AuthController', 'register']);
$router->GET('/logout', ['AuthController', 'logout']);

// Teacher Routes

$router->GET('/teacher/dashboard', ['TeacherController', 'dashboard']);



$router->dispatch($_SERVER['REQUEST_URI']);





















