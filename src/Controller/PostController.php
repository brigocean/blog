<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\NotFoundException;
use App\Core\View;
use App\Repository\CategoryRepository;
use App\Repository\PostRepository;

final class PostController
{
    public function __construct(
        private readonly View $view,
        private readonly PostRepository $posts,
        private readonly CategoryRepository $categories,
        private readonly int $similarLimit,
    ) {
    }

    public function show(string $slug): void
    {
        $post = $this->posts->findBySlug($slug) ?? throw new NotFoundException();

        $this->posts->incrementViews($post['id']);
        $post['views']++;

        $this->view->render('post.tpl', [
            'post' => $post,
            'paragraphs' => preg_split('/\R{2,}/', trim($post['content'])),
            'categories' => $this->categories->findByPost($post['id']),
            'similar' => $this->posts->findSimilar($post['id'], $this->similarLimit),
        ]);
    }
}
