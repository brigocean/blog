<?php

declare(strict_types=1);

use App\Core\Database;

$rootDir = dirname(__DIR__);

require $rootDir . '/vendor/autoload.php';

$config = require $rootDir . '/config/config.php';
$db = $config['db'];

$pdo = Database::connect($db, false);
$pdo->exec(sprintf(
    'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
    str_replace('`', '', $db['name'])
));

$pdo = Database::connect($db);
$pdo->exec(file_get_contents($rootDir . '/database/schema.sql'));

echo "Схема базы данных \"{$db['name']}\" применена." . PHP_EOL;
