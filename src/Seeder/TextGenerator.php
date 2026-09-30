<?php

declare(strict_types=1);

namespace App\Seeder;

final class TextGenerator
{
    private const TITLE_STARTS = [
        'Как выбрать', 'Почему стоит попробовать', 'Что нужно знать про', 'Простой способ изучить',
        'Пять идей, как улучшить', 'Разбираемся, как устроен', 'Полное руководство про', 'Неочевидные факты про',
    ];

    private const TITLE_SUBJECTS = [
        'первый ноутбук', 'утренний кофе', 'городской велосипед', 'домашний сад', 'новый язык программирования',
        'маршрут выходного дня', 'рабочий день', 'семейный бюджет', 'итальянскую пасту', 'бег по утрам',
        'осенний гардероб', 'умный дом', 'небольшой бизнес', 'поход в горы', 'чтение книг',
        'спокойный сон', 'современное искусство', 'электромобиль', 'домашний хлеб', 'удаленную работу',
    ];

    private const WORDS = [
        'время', 'идея', 'город', 'проект', 'решение', 'опыт', 'команда', 'задача', 'результат', 'система',
        'путь', 'вопрос', 'подход', 'история', 'момент', 'шаг', 'деталь', 'привычка', 'цель', 'инструмент',
        'важный', 'простой', 'новый', 'полезный', 'сложный', 'интересный', 'быстрый', 'удобный', 'главный', 'личный',
        'помогает', 'позволяет', 'меняет', 'требует', 'открывает', 'создает', 'показывает', 'дает', 'делает', 'находит',
        'всегда', 'часто', 'иногда', 'сегодня', 'действительно', 'постепенно', 'заметно', 'легко', 'снова', 'вместе',
    ];

    public function title(): string
    {
        return $this->pick(self::TITLE_STARTS) . ' ' . $this->pick(self::TITLE_SUBJECTS);
    }

    public function sentence(int $min = 6, int $max = 14): string
    {
        $words = [];

        for ($i = random_int($min, $max); $i > 0; $i--) {
            $words[] = $this->pick(self::WORDS);
        }

        $sentence = implode(' ', $words);

        return mb_strtoupper(mb_substr($sentence, 0, 1)) . mb_substr($sentence, 1) . '.';
    }

    public function paragraph(int $sentences): string
    {
        return implode(' ', array_map(fn () => $this->sentence(), range(1, $sentences)));
    }

    public function text(int $paragraphs): string
    {
        return implode("\n\n", array_map(fn () => $this->paragraph(random_int(3, 6)), range(1, $paragraphs)));
    }

    private function pick(array $items): string
    {
        return $items[array_rand($items)];
    }
}
