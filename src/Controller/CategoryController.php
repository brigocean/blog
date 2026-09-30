<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\NotFoundException;
use App\Core\Paginator;
use App\Core\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;
use App\Repository\PostSort;

final class CategoryController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categories,
        private readonly PostRepository $posts,
        private readonly int $perPage,
    ) {
    }

    public function show(string $slug, array $query): void
    {
        $category = $this->categories->findBySlug($slug) ?? throw new NotFoundException();
        $sort = PostSort::fromQuery($query['sort'] ?? null);

        $paginator = new Paginator(
            Paginator::pageFromQuery($query),
            $this->perPage,
            $this->posts->countByCategory($category['id']),
        );

        if ($paginator->isOutOfRange()) {
            throw new NotFoundException();
        }

        $this->view->render('category.tpl', [
            'category' => $category,
            'posts' => $this->posts->findByCategory($category['id'], $sort, $paginator->perPage, $paginator->offset),
            'pagination' => $paginator,
            'sort' => $sort->value,
            'sorts' => PostSort::options(),
        ]);
    }
}
