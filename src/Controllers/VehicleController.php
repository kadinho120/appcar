<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Config\Database;
use App\Models\Vehicle;

class VehicleController
{
    private Vehicle $vehicleModel;

    public function __construct(Database $db)
    {
        $this->vehicleModel = new Vehicle($db->getConnection());
    }

    public function add(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Method not allowed']);
            return;
        }

        $make = $_POST['make'] ?? '';
        $model = $_POST['model'] ?? '';
        $year = $_POST['year'] ?? '';
        $license_plate = $_POST['license_plate'] ?? '';

        if (!$make || !$model || !$year) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Preencha todos os campos obrigatórios.']);
            return;
        }

        $success = $this->vehicleModel->create([
            'user_id' => $_SESSION['user_id'],
            'make' => $make,
            'model' => $model,
            'year' => $year,
            'license_plate' => $license_plate
        ]);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
    }

    public function delete(): void
    {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: /garage');
            exit;
        }

        $this->vehicleModel->delete((int) $id, (int) $_SESSION['user_id']);
        header('Location: /garage');
        exit;
    }
}
