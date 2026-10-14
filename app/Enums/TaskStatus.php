<?php

namespace App\Enums;

enum TaskStatus: string
{
    case Todo       = 'todo';         // 未対応
    case InProgress = 'in_progress';  // 対応中
    case Done       = 'done';         // 完了

    public function label(): string
    {
        return match ($this) {
            self::Todo       => '未対応',
            self::InProgress => '対応中',
            self::Done       => '完了',
        };
    }

    /** セレクトボックス用: ['todo' => '未対応', ...] */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $s) => [$s->value => $s->label()])
            ->all();
    }
}