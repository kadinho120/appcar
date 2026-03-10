<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Database;
use App\Models\Diagnostic;
use Exception;

class DiagnosticController
{
    private Diagnostic $diagnosticModel;

    public function __construct(Database $db)
    {
        $this->diagnosticModel = new Diagnostic($db->getConnection());
    }

    public function save(): void
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Não autorizado']);
            return;
        }

        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $userId = $_SESSION['user_id'];
            $resultJson = $data['result'] ?? [];
            $symptoms = $data['symptoms'] ?? '';

            if (empty($resultJson)) {
                throw new Exception("Resultado do diagnóstico é obrigatório.");
            }

            $success = $this->diagnosticModel->save($userId, $resultJson, $symptoms);

            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function history(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $history = $this->diagnosticModel->getHistoryByUserId($userId);

        require '../views/dashboard/history.php';
    }
}
