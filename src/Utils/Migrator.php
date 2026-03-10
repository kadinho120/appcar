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
            $sqlFile = __DIR__ . '/../../database/migration.sql';
            if (!file_exists($sqlFile)) {
                throw new Exception("Migration file not found at $sqlFile");
            }

            $sql = file_get_contents($sqlFile);
            if ($sql === false) {
                throw new Exception("Failed to read migration file.");
            }

            // Execute the whole migration script
            // SQL uses CREATE TABLE IF NOT EXISTS so it's safe to run multiple times
            $db->exec($sql);
        } catch (Exception $e) {
            // Log error or handle it (silently fail if preferred, but here we want to know why migration failed)
            error_log("Migration Error: " . $e->getMessage());
        }
    }
}
