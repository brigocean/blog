<?php

declare(strict_types=1);

namespace App\Seeder;

use PDO;
use Throwable;

final class Seeder
{
    private const CATEGORIES = [
        ['Технологии', 'Новости и разборы из мира гаджетов, софта и разработки.'],
        ['Наука', 'Открытия, исследования и объяснения сложного простым языком.'],
        ['Путешествия', 'Маршруты, советы и впечатления из поездок по миру.'],
        ['Кулинария', 'Рецепты, продукты и секреты домашней кухни.'],
        ['Спорт', 'Тренировки, соревнования и здоровый образ жизни.'],
        ['Культура', 'Книги, кино, музыка и искусство.'],
        ['Здоровье', 'Как заботиться о себе и сохранять энергию.'],
        ['Бизнес', 'Истории компаний, управление и финансы.'],
    ];

    private const PALETTE = [
        ['#4263eb', '#9775fa'], ['#0ca678', '#66d9e8'], ['#f76707', '#ffd43b'], ['#e64980', '#ff8787'],
        ['#1c7ed6', '#3bc9db'], ['#5f3dc4', '#e599f7'], ['#2b8a3e', '#a9e34b'], ['#495057', '#adb5bd'],
    ];

    public function __construct(
        private readonly PDO $pdo,
        private readonly TextGenerator $text,
        private readonly Slugger $slugger,
        private readonly string $uploadDir,
        private readonly string $uploadUrl,
    ) {
    }

    public function run(int $categoriesCount, int $postsCount): void
    {
        $this->clear();

        $this->pdo->beginTransaction();

        try {
            $categoryIds = $this->seedCategories($categoriesCount);
            $this->seedPosts($postsCount, $categoryIds);
            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();

            throw $e;
        }
    }

    private function clear(): void
    {
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
        $this->pdo->exec('TRUNCATE TABLE category_post');
        $this->pdo->exec('TRUNCATE TABLE posts');
        $this->pdo->exec('TRUNCATE TABLE categories');
        $this->pdo->exec('SET FOREIGN_KEY_CHECKS = 1');

        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }

        array_map('unlink', glob($this->uploadDir . '/*.svg') ?: []);
    }

    private function seedCategories(int $count): array
    {
        $stmt = $this->pdo->prepare('INSERT INTO categories (name, slug, description) VALUES (:name, :slug, :description)');
        $ids = [];

        for ($i = 0; $i < $count; $i++) {
            [$name, $description] = self::CATEGORIES[$i % count(self::CATEGORIES)];
            $round = intdiv($i, count(self::CATEGORIES));
            $name = $round > 0 ? $name . ' ' . ($round + 1) : $name;

            $stmt->execute([
                'name' => $name,
                'slug' => $this->slugger->slugify($name),
                'description' => $description,
            ]);
            $ids[] = (int) $this->pdo->lastInsertId();
        }

        return $ids;
    }

    private function seedPosts(int $count, array $categoryIds): void
    {
        $postStmt = $this->pdo->prepare(
            'INSERT INTO posts (title, slug, description, content, image, views, published_at)
             VALUES (:title, :slug, :description, :content, :image, :views, :published_at)'
        );
        $linkStmt = $this->pdo->prepare('INSERT INTO category_post (category_id, post_id) VALUES (:category_id, :post_id)');

        for ($i = 0; $i < $count; $i++) {
            $title = $this->text->title();
            $slug = $this->slugger->slugify($title);

            $postStmt->execute([
                'title' => $title,
                'slug' => $slug,
                'description' => $this->text->paragraph(2),
                'content' => $this->text->text(random_int(4, 8)),
                'image' => $this->createImage($slug, $title),
                'views' => random_int(0, 5000),
                'published_at' => date('Y-m-d H:i:s', time() - random_int(0, 365 * 24 * 3600)),
            ]);
            $postId = (int) $this->pdo->lastInsertId();

            foreach ($this->randomCategories($categoryIds) as $categoryId) {
                $linkStmt->execute(['category_id' => $categoryId, 'post_id' => $postId]);
            }
        }
    }

    private function randomCategories(array $categoryIds): array
    {
        $amount = random_int(1, min(3, count($categoryIds)));
        $keys = (array) array_rand($categoryIds, $amount);

        return array_map(static fn (int $key) => $categoryIds[$key], $keys);
    }

    private function createImage(string $slug, string $title): string
    {
        [$from, $to] = self::PALETTE[array_rand(self::PALETTE)];

        $svg = sprintf(
            '<svg xmlns="http://www.w3.org/2000/svg" width="1200" height="675" viewBox="0 0 1200 675">'
            . '<defs><linearGradient id="g" x1="0" y1="0" x2="1" y2="1">'
            . '<stop offset="0" stop-color="%s"/><stop offset="1" stop-color="%s"/></linearGradient></defs>'
            . '<rect width="1200" height="675" fill="url(#g)"/>'
            . '<circle cx="%d" cy="%d" r="%d" fill="#fff" fill-opacity=".15"/>'
            . '<circle cx="%d" cy="%d" r="%d" fill="#fff" fill-opacity=".1"/>'
            . '<text x="60" y="615" font-family="Arial, sans-serif" font-size="44" font-weight="700" fill="#fff">%s</text>'
            . '</svg>',
            $from,
            $to,
            random_int(700, 1100),
            random_int(80, 300),
            random_int(120, 260),
            random_int(100, 600),
            random_int(300, 500),
            random_int(60, 160),
            htmlspecialchars($title, ENT_XML1),
        );

        file_put_contents($this->uploadDir . '/' . $slug . '.svg', $svg);

        return $this->uploadUrl . '/' . $slug . '.svg';
    }
}
