<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Database;
use App\Models\User;
use App\Services\AuthService;
use Exception;

class AuthController
{
    private AuthService $authService;

    public function __construct(Database $db)
    {
        $userModel = new User($db->getConnection());
        $this->authService = new AuthService($userModel);
    }

    public function login(): void
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            if (empty($email) || empty($password)) {
                throw new Exception("Email e senha são obrigatórios.");
            }

            $user = $this->authService->login($email, $password);

            if ($user) {
                header('Location: /index');
                exit;
            } else {
                throw new Exception("Credenciais inválidas.");
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            require '../views/auth/login.php';
        }
    }

    public function register(): void
    {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        try {
            if (empty($name) || empty($email) || empty($password)) {
                throw new Exception("Todos os campos são obrigatórios.");
            }

            if ($this->authService->register($name, $email, $password)) {
                header('Location: /login?registered=1');
                exit;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
            require '../views/auth/register.php';
        }
    }
}
