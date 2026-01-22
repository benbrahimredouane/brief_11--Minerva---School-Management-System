<?php

class BaseController
{
    protected function render($view, $data = [])
    {
        $viewFile = __DIR__ . '/../views/' . $view . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo 'View not found';
            return;
        }

        extract($data);
        require $viewFile;
    }
}

