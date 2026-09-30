<?php

declare(strict_types=1);

namespace App\Repository;

enum PostSort: string
{
    case Date = 'date';
    case Views = 'views';

    public static function fromQuery(mixed $value): self
    {
        return is_string($value) ? (self::tryFrom($value) ?? self::Date) : self::Date;
    }

    public static function options(): array
    {
        return array_combine(
            array_map(static fn (self $sort) => $sort->value, self::cases()),
            array_map(static fn (self $sort) => $sort->label(), self::cases()),
        );
    }

    public function label(): string
    {
        return match ($this) {
            self::Date => 'По дате публикации',
            self::Views => 'По просмотрам',
        };
    }

    public function orderBy(): string
    {
        return match ($this) {
            self::Date => 'p.published_at DESC, p.id DESC',
            self::Views => 'p.views DESC, p.published_at DESC, p.id DESC',
        };
    }
}
