<?php

namespace App\Enums;

enum EntryTax: string
{
    case Taxable = 'ضريبة';
    case TaxFree = 'بدون ضريبة';
}
