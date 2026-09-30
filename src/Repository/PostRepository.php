<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class PostRepository
{
    private const CARD_COLUMNS = 'p.id, p.title, p.slug, p.description, p.image, p.views, p.published_at';

    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::CARD_COLUMNS . ', p.content FROM posts p WHERE p.slug = :slug'
        );
        $stmt->execute(['slug' => $slug]);

        return $stmt->fetch() ?: null;
    }

    public function countByCategory(int $categoryId): int
    {
        $stmt = $this->pdo->prepare('SELECT COUNT(*) FROM category_post WHERE category_id = :category_id');
        $stmt->execute(['category_id' => $categoryId]);

        return (int) $stmt->fetchColumn();
    }

    public function findByCategory(int $categoryId, PostSort $sort, int $limit, int $offset): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::CARD_COLUMNS . '
             FROM posts p
             JOIN category_post cp ON cp.post_id = p.id
             WHERE cp.category_id = :category_id
             ORDER BY ' . $sort->orderBy() . '
             LIMIT :limit OFFSET :offset'
        );
        $stmt->bindValue('category_id', $categoryId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue('offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function findSimilar(int $postId, int $limit): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT ' . self::CARD_COLUMNS . ', COUNT(*) AS common_categories
             FROM category_post own
             JOIN category_post other
                 ON other.category_id = own.category_id AND other.post_id <> own.post_id
             JOIN posts p ON p.id = other.post_id
             WHERE own.post_id = :post_id
             GROUP BY p.id
             ORDER BY common_categories DESC, p.published_at DESC, p.id DESC
             LIMIT :limit'
        );
        $stmt->bindValue('post_id', $postId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function incrementViews(int $postId): void
    {
        $stmt = $this->pdo->prepare('UPDATE posts SET views = views + 1 WHERE id = :id');
        $stmt->execute(['id' => $postId]);
    }
}
