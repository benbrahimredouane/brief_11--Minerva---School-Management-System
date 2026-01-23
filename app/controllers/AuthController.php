<?php

class AuthController{

 public function showlogin() {
        require __DIR__ . '/../views/login.view.php';
    }


 public function showregister() {
        require __DIR__ . '/../views/register.view.php';
    }
}

?>