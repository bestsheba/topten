<?php

namespace App\Enums;

enum OrderSource: string
{
    case WEB = 'web';
    case MOBILE = 'mobile';
    case POS = 'pos';
    case OFFLINE = 'offline';

    public function label(): string
    {
        return match ($this) {
            self::WEB => 'Website',
            self::MOBILE => 'Mobile App',
            self::POS => 'POS Terminal',
            self::OFFLINE => 'Offline',
        };
    }

    public function badge(): string
    {
        return match ($this) {
            self::WEB => '<span class="badge badge-info">Website</span>',
            self::MOBILE => '<span class="badge badge-success">Mobile App</span>',
            self::POS => '<span class="badge badge-warning">POS Terminal</span>',
            self::OFFLINE => '<span class="badge badge-secondary">Offline</span>',
        };
    }

    public static function all(): array
    {
        return [
            self::WEB->value => self::WEB->label(),
            self::MOBILE->value => self::MOBILE->label(),
            self::POS->value => self::POS->label(),
            self::OFFLINE->value => self::OFFLINE->label(),
        ];
    }
}
