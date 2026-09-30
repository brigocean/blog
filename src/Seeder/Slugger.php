<?php

declare(strict_types=1);

namespace App\Seeder;

final class Slugger
{
    private const MAP = [
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'e',
        'ж' => 'zh', 'з' => 'z', 'и' => 'i', 'й' => 'y', 'к' => 'k', 'л' => 'l', 'м' => 'm',
        'н' => 'n', 'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u',
        'ф' => 'f', 'х' => 'h', 'ц' => 'ts', 'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sch', 'ъ' => '',
        'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
    ];

    private array $used = [];

    public function slugify(string $text): string
    {
        $slug = strtr(mb_strtolower($text), self::MAP);
        $slug = trim(preg_replace('/[^a-z0-9]+/', '-', $slug), '-');
        $slug = rtrim(substr($slug, 0, 80), '-') ?: 'item';

        $unique = $slug;

        for ($i = 2; isset($this->used[$unique]); $i++) {
            $unique = $slug . '-' . $i;
        }

        $this->used[$unique] = true;

        return $unique;
    }
}
