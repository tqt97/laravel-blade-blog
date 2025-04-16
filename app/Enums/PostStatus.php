<?php

namespace App\Enums;

enum PostStatus: string
{
    case DRAFT = 'draft';
    case PUBLISHED = 'published';

    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PUBLISHED => 'Published',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'warning',
            self::PUBLISHED => 'success',
        };
    }
}
