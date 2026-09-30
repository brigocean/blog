<?php

declare(strict_types=1);

use App\Core\Database;
use App\Seeder\Seeder;
use App\Seeder\Slugger;
use App\Seeder\TextGenerator;

$rootDir = dirname(__DIR__);

require $rootDir . '/vendor/autoload.php';

$config = require $rootDir . '/config/config.php';
$options = getopt('', ['categories::', 'posts::']);

$categoriesCount = max(1, (int) ($options['categories'] ?? 6));
$postsCount = max(1, (int) ($options['posts'] ?? 60));

$seeder = new Seeder(
    Database::connect($config['db']),
    new TextGenerator(),
    new Slugger(),
    $rootDir . '/public/uploads/posts',
    '/uploads/posts',
);

$seeder->run($categoriesCount, $postsCount);

echo "Создано категорий: {$categoriesCount}, статей: {$postsCount}." . PHP_EOL;
