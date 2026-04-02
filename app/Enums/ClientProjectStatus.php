<?php

namespace App\Enums;

enum ClientProjectStatus: string
{
    case Lead = 'lead';
    case Active = 'active';
    case OnHold = 'on_hold';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Lead => 'عميل محتمل',
            self::Active => 'نشط',
            self::OnHold => 'معلق',
            self::Completed => 'مكتمل',
            self::Cancelled => 'ملغي',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Lead => 'gray',
            self::Active => 'blue',
            self::OnHold => 'yellow',
            self::Completed => 'green',
            self::Cancelled => 'red',
        };
    }
}
