<?php

declare(strict_types=1);

namespace App\Core;

final class Paginator
{
    public readonly int $pages;
    public readonly int $offset;

    public function __construct(
        public readonly int $page,
        public readonly int $perPage,
        public readonly int $total,
    ) {
        $this->pages = max(1, (int) ceil($total / $perPage));
        $this->offset = ($page - 1) * $perPage;
    }

    public static function pageFromQuery(array $query): int
    {
        $page = filter_var($query['page'] ?? 1, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);

        if ($page === false) {
            throw new NotFoundException();
        }

        return $page;
    }

    public function isOutOfRange(): bool
    {
        return $this->page > $this->pages;
    }
}
