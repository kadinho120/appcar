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
            $vehicleInfo = $data['vehicle_info'] ?? null;

            if (empty($resultJson)) {
                throw new Exception("Resultado do diagnóstico é obrigatório.");
            }

            $success = $this->diagnosticModel->save($userId, $resultJson, $symptoms, $vehicleInfo);

            echo json_encode(['success' => $success]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function index(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $historyRaw = $this->diagnosticModel->getHistoryByUserId($userId);

        // Decode JSON fields for the view
        $history = array_map(function ($item) {
            $item['vehicle_info'] = json_decode((string) ($item['vehicle_info'] ?? ''), true) ?? [];
            $item['result_json'] = json_decode((string) ($item['result_json'] ?? ''), true) ?? [];
            return $item;
        }, $historyRaw);

        // Fetch vehicles for the "New Diagnostic" selection
        $vehicleModel = new \App\Models\Vehicle($this->diagnosticModel->getConnection());
        $vehicles = $vehicleModel->findByUser($userId);

        require '../views/dashboard/index.php';
    }

    public function chat(): void
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $diagnosticId = $_GET['diagnostic_id'] ?? null;
        $selectedVehicle = null;
        $historicalMessages = [];

        if ($diagnosticId) {
            $diagnostic = $this->diagnosticModel->findById((int) $diagnosticId);
            if ($diagnostic && $diagnostic['user_id'] == $_SESSION['user_id']) {
                $selectedVehicle = json_decode((string) ($diagnostic['vehicle_info'] ?? ''), true) ?? [];
                $historicalMessages = [
                    ['role' => 'user', 'content' => $diagnostic['symptoms'] ?? ''],
                    ['role' => 'assistant', 'content' => (json_decode((string) ($diagnostic['result_json'] ?? ''), true) ?? [])['text'] ?? '']
                ];
            }
        }

        if (!$selectedVehicle) {
            $vehicleId = $_GET['vehicle_id'] ?? null;
            if (!$vehicleId) {
                header('Location: /index');
                exit;
            }

            $vehicleModel = new \App\Models\Vehicle($this->diagnosticModel->getConnection());
            $vehicle = $vehicleModel->findByUser((int) $_SESSION['user_id']);

            foreach ($vehicle as $v) {
                if ($v['id'] == $vehicleId) {
                    $selectedVehicle = $v;
                    break;
                }
            }
        }

        if (!$selectedVehicle) {
            header('Location: /index');
            exit;
        }

        require '../views/dashboard/chat.php';
    }

    public function history(): void
    {
        $this->index(); // Reuse the same landing page logic
    }
}
