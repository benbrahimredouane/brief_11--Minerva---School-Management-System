<?php

class Router
{

private $router = [];

public function GET($path,$action){
    $this->router['GET'][$path] = $action;
}
public function POST($path,$action){
    $this->router['POST'][$path] = $action;
}

public function dispatch($url){
    $path = parse_url($url,PHP_URL_PATH);
    $method = $_SERVER['REQUEST_METHOD'];
    $action = $this->router[$method][$path] ?? null;

    if(!$action){
             http_response_code(404);
             echo '404 Not Found';
             return;
    }
    $controllerName = $action[0];
    $methodName = $action[1];

    require_once __DIR__ . '/../controllers/'.$controllerName.'.php';
    
    $controller = new $controllerName();
    $controller->$methodName();

}
}