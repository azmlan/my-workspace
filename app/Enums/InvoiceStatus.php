<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case Unpaid = 'unpaid';
    case Partial = 'partial';
    case Paid = 'paid';

    public function label(): string
    {
        return match ($this) {
            self::Unpaid => 'غير مدفوعة',
            self::Partial => 'مدفوعة جزئياً',
            self::Paid => 'مدفوعة',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Unpaid => 'red',
            self::Partial => 'yellow',
            self::Paid => 'green',
        };
    }
}
