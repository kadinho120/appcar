<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Diagnostic
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function save(int $userId, array $resultJson, ?string $symptoms = null, ?array $vehicleInfo = null): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO diagnostics (user_id, result_json, symptoms, vehicle_info) 
            VALUES (:user_id, :result_json, :symptoms, :vehicle_info)
        ");

        return $stmt->execute([
            'user_id' => $userId,
            'result_json' => json_encode($resultJson),
            'symptoms' => $symptoms,
            'vehicle_info' => $vehicleInfo ? json_encode($vehicleInfo) : null
        ]);
    }

    public function getHistoryByUserId(int $userId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM diagnostics WHERE user_id = :user_id ORDER BY created_at DESC");
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll();
    }
}
