<?php

declare(strict_types=1);

namespace App\Utils;

use PDO;
use Exception;

class Migrator
{
    public static function run(PDO $db): void
    {
        try {
            // Check if users table exists
            $stmt = $db->query("SELECT 1 FROM information_schema.tables WHERE table_name = 'users' LIMIT 1");
            $exists = $stmt->fetch();

            if (!$exists) {
                $sqlFile = __DIR__ . '/../../database/migration.sql';
                if (!file_exists($sqlFile)) {
                    throw new Exception("Migration file not found at $sqlFile");
                }

                $sql = file_get_contents($sqlFile);
                if ($sql === false) {
                    throw new Exception("Failed to read migration file.");
                }

                $db->exec($sql);
            }
        } catch (Exception $e) {
            // Log error or handle it (silently fail if preferred, but here we want to know why migration failed)
            error_log("Migration Error: " . $e->getMessage());
        }
    }
}
