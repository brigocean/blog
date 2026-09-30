<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

final class Database
{
    public static function connect(array $config, bool $withDatabase = true): PDO
    {
        $dsn = sprintf('mysql:host=%s;port=%d;charset=utf8mb4', $config['host'], $config['port']);

        if ($withDatabase) {
            $dsn .= ';dbname=' . $config['name'];
        }

        return new PDO($dsn, $config['user'], $config['password'], [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }
}
