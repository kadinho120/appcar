<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Vehicle
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function create(array $data): bool
    {
        $sql = "INSERT INTO vehicles (user_id, make, model, year, license_plate) 
                VALUES (:user_id, :make, :model, :year, :license_plate)";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'user_id' => $data['user_id'],
            'make' => $data['make'],
            'model' => $data['model'],
            'year' => (int) $data['year'],
            'license_plate' => $data['license_plate'] ?? null
        ]);
    }

    public function findByUser(int $userId): array
    {
        $sql = "SELECT * FROM vehicles WHERE user_id = :user_id ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute(['user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function delete(int $id, int $userId): bool
    {
        $sql = "DELETE FROM vehicles WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId
        ]);
    }

    public function update(int $id, int $userId, array $data): bool
    {
        $sql = "UPDATE vehicles 
                SET make = :make, model = :model, year = :year, license_plate = :license_plate 
                WHERE id = :id AND user_id = :user_id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            'id' => $id,
            'user_id' => $userId,
            'make' => $data['make'],
            'model' => $data['model'],
            'year' => (int) $data['year'],
            'license_plate' => $data['license_plate'] ?? null
        ]);
    }
}
