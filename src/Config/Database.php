<?php

declare(strict_types=1);

namespace App\Config;

use PDO;
use PDOException;
use RuntimeException;

class Database
{
    private ?PDO $connection = null;

    public function getConnection(): PDO
    {
        if ($this->connection === null) {
            $databaseUrl = getenv('DATABASE_URL');

            if (!$databaseUrl) {
                throw new RuntimeException('DATABASE_URL environment variable is not set.');
            }

            $url = parse_url($databaseUrl);

            if ($url === false) {
                throw new RuntimeException('Invalid DATABASE_URL format.');
            }

            $host = $url['host'] ?? 'localhost';
            $port = $url['port'] ?? '5432';
            $user = $url['user'] ?? '';
            $pass = $url['pass'] ?? '';
            $dbName = ltrim($url['path'] ?? '', '/');

            $dsn = "pgsql:host=$host;port=$port;dbname=$dbName";

            try {
                $this->connection = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]);
            } catch (PDOException $e) {
                throw new RuntimeException("Database connection failed: " . $e->getMessage());
            }
        }

        return $this->connection;
    }
}
