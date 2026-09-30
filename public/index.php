<?php

declare(strict_types=1);

use App\Controller\CategoryController;
use App\Controller\HomeController;
use App\Controller\PostController;
use App\Core\Database;
use App\Core\NotFoundException;
use App\Core\Router;
use App\Core\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

if (PHP_SAPI === 'cli-server' && is_file(__DIR__ . parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))) {
    return false;
}

$rootDir = dirname(__DIR__);

require $rootDir . '/vendor/autoload.php';

$config = require $rootDir . '/config/config.php';
$view = new View($rootDir);

try {
    $pdo = Database::connect($config['db']);
    $categories = new CategoryRepository($pdo);
    $posts = new PostRepository($pdo);

    $router = new Router();

    $router->get('/', static fn () => (new HomeController($view, $categories, $config['latest_per_category']))
        ->index());

    $router->get('/category/{slug}', static fn (string $slug) => (new CategoryController($view, $categories, $posts, $config['per_page']))
        ->show($slug, $_GET));

    $router->get('/post/{slug}', static fn (string $slug) => (new PostController($view, $posts, $categories, $config['similar_limit']))
        ->show($slug));

    $router->dispatch($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
} catch (NotFoundException) {
    $view->render('error.tpl', ['code' => 404, 'message' => 'Страница не найдена'], 404);
} catch (Throwable $e) {
    error_log((string) $e);
    $view->render('error.tpl', ['code' => 500, 'message' => 'Что-то пошло не так. Попробуйте позже.'], 500);
}
