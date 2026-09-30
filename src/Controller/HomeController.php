<?php

declare(strict_types=1);

namespace App\Controller;

use App\Core\View;
use App\Repository\CategoryRepository;

final class HomeController
{
    public function __construct(
        private readonly View $view,
        private readonly CategoryRepository $categories,
        private readonly int $postsPerCategory,
    ) {
    }

    public function index(): void
    {
        $this->view->render('home.tpl', [
            'categories' => $this->categories->findWithLatestPosts($this->postsPerCategory),
        ]);
    }
}
