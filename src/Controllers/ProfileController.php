<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Database;
use App\Models\User;
use Exception;

class ProfileController
{
    private User $userModel;

    public function __construct(Database $db)
    {
        $this->userModel = new User($db->getConnection());
    }

    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        require '../views/dashboard/profile.php';
    }

    public function settings(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $user = $this->userModel->findById((int) $_SESSION['user_id']);
        require '../views/dashboard/settings.php';
    }

    public function update(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /settings');
            exit;
        }

        try {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');

            if (empty($name) || empty($email)) {
                throw new Exception("Nome e e-mail são obrigatórios.");
            }

            $success = $this->userModel->update((int) $_SESSION['user_id'], $name, $email);

            if ($success) {
                $_SESSION['user_name'] = $name;
                $_SESSION['user_email'] = $email;
                $_SESSION['success'] = "Perfil atualizado com sucesso!";
            } else {
                throw new Exception("Erro ao atualizar perfil.");
            }

        } catch (Exception $e) {
            $_SESSION['error'] = $e->getMessage();
        }

        header('Location: /settings');
        exit;
    }
}
