<?php

declare(strict_types=1);

namespace App\Repository;

use PDO;

final class CategoryRepository
{
    public function __construct(private readonly PDO $pdo)
    {
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, name, slug, description FROM categories WHERE slug = :slug');
        $stmt->execute(['slug' => $slug]);

        return $stmt->fetch() ?: null;
    }

    public function findByPost(int $postId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.id, c.name, c.slug
             FROM categories c
             JOIN category_post cp ON cp.category_id = c.id
             WHERE cp.post_id = :post_id
             ORDER BY c.name'
        );
        $stmt->execute(['post_id' => $postId]);

        return $stmt->fetchAll();
    }

    public function findWithLatestPosts(int $limit): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT c.id AS category_id, c.name AS category_name, c.slug AS category_slug,
                    c.description AS category_description,
                    p.id, p.title, p.slug, p.description, p.image, p.views, p.published_at
             FROM (
                 SELECT cp.category_id, cp.post_id,
                        ROW_NUMBER() OVER (
                            PARTITION BY cp.category_id
                            ORDER BY p.published_at DESC, p.id DESC
                        ) AS position
                 FROM category_post cp
                 JOIN posts p ON p.id = cp.post_id
             ) ranked
             JOIN categories c ON c.id = ranked.category_id
             JOIN posts p ON p.id = ranked.post_id
             WHERE ranked.position <= :limit
             ORDER BY c.name, ranked.position'
        );
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        $categories = [];

        foreach ($stmt->fetchAll() as $row) {
            $id = $row['category_id'];
            $categories[$id] ??= [
                'name' => $row['category_name'],
                'slug' => $row['category_slug'],
                'description' => $row['category_description'],
                'posts' => [],
            ];
            $categories[$id]['posts'][] = [
                'id' => $row['id'],
                'title' => $row['title'],
                'slug' => $row['slug'],
                'description' => $row['description'],
                'image' => $row['image'],
                'views' => $row['views'],
                'published_at' => $row['published_at'],
            ];
        }

        return array_values($categories);
    }
}
