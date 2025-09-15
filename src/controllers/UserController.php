<?php

// C:\laragon\www\mvc\src\controllers\UserController.php

namespace Reybi\MVC\Controllers;

use Reybi\MVC\Models\User;

class UserController extends Controller
{
    public function register()
    {
        // Display the registration form.
        $this->view('register');
    }

    public function handleRegister()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            // Basic validation
            $errors = [];

            if (empty($username)) {
                $errors[] = "Username is required";
            }

            if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Valid email is required";
            }

            if (empty($password) || strlen($password) < 6) {
                $errors[] = "Password must be at least 6 characters";
            }

            if (empty($errors)) {
                $user = new User();
                $result = $user->create($username, $email, $password);

                if ($result) {
                    $this->view('register', ['success' => 'User registered successfully!']);
                } else {
                    $this->view('register', ['error' => 'Registration failed. Username or email might already exist.']);
                }
            } else {
                $this->view('register', ['errors' => $errors]);
            }
        }
    }

    public function login()
    {
        $this->view('login');
    }

    public function handleLogin()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!empty($username) && !empty($password)) {
                $user = new User();
                $userData = $user->authenticate($username, $password);

                if ($userData) {
                    session_start();
                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['username'] = $userData['username'];

                    header('Location: /dashboard');
                    exit;
                } else {
                    $this->view('login', ['error' => 'Invalid username or password']);
                }
            } else {
                $this->view('login', ['error' => 'Please fill in all fields']);
            }
        }
    }

    public function dashboard()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $this->view('dashboard', ['username' => $_SESSION['username']]);
    }

    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /');
        exit;
    }
}
