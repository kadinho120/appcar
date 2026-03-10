<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\User;
use Exception;

class AuthService
{
    private User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function register(string $name, string $email, string $password): bool
    {
        if ($this->userModel->findByEmail($email)) {
            throw new Exception("Email já cadastrado.");
        }

        $passwordHash = password_hash($password, PASSWORD_BCRYPT);
        return $this->userModel->create($name, $email, $passwordHash);
    }

    public function login(string $email, string $password): ?array
    {
        $user = $this->userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            return $user;
        }

        return null;
    }

    public function logout(): void
    {
        session_destroy();
    }
}
