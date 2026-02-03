<?php
require __DIR__ . '/../models/User.php';

class AuthController{

    public function showlogin() {
        require __DIR__ . '/../views/login.view.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required';
            header('Location: /login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->findUserByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid email or password';
            header('Location: /login');
            exit;
        }

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] === 'teacher') {
            header('Location: /teacher/dashboard');
        } elseif ($user['role'] === 'student') {
            header('Location: /student/dashboard');
        } else {
            header('Location: /');
        }
        exit;
    }

    public function showregister() {
        require __DIR__ . '/../views/register.view.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $role = 'teacher'; // Always set role to teacher

        // Validate inputs
        if (empty($firstName) || empty($lastName) || empty($email) || empty($password) || empty($confirmPassword)) {
            $_SESSION['error'] = 'All fields are required';
            header('Location: /register');
            exit;
        }

        if ($password !== $confirmPassword) {
            $_SESSION['error'] = 'Passwords do not match';
            header('Location: /register');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Password must be at least 6 characters';
            header('Location: /register');
            exit;
        }

        // Check if email already exists
        $userModel = new User();
        $existingUser = $userModel->findUserByEmail($email);
        
        if ($existingUser) {
            $_SESSION['error'] = 'Email already registered';
            header('Location: /register');
            exit;
        }

        // Create full name
        $fullName = $firstName . ' ' . $lastName;

        // Create user in database
        if ($userModel->create($fullName, $email, $password, $role)) {
            $_SESSION['success'] = 'Account created successfully! Please log in.';
            header('Location: /login');
            exit;
        } else {
            $_SESSION['error'] = 'Error creating account. Please try again.';
            header('Location: /register');
            exit;
        }
    }

    public function logout() {
        session_destroy();
        header('Location: /');
        exit;
    }
}