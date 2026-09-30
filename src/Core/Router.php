<?php

declare(strict_types=1);

namespace App\Core;

final class Router
{
    private array $routes = [];

    public function get(string $pattern, callable $handler): void
    {
        $regex = preg_replace('#\{(\w+)\}#', '(?P<$1>[a-z0-9-]+)', $pattern);
        $this->routes['#^' . $regex . '$#'] = $handler;
    }

    public function dispatch(string $method, string $uri): void
    {
        if ($method !== 'GET' && $method !== 'HEAD') {
            throw new NotFoundException();
        }

        $path = parse_url($uri, PHP_URL_PATH) ?: '/';
        $path = $path === '/' ? $path : rtrim($path, '/');

        foreach ($this->routes as $regex => $handler) {
            if (preg_match($regex, $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $handler(...$params);

                return;
            }
        }

        throw new NotFoundException();
    }
}
