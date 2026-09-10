<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'product_name',
        'sku',
        'option_name_1',
        'option_value_1',
        'option_name_2',
        'option_value_2',
        'option_name_3',
        'option_value_3',
        'retail_price',
        'cost_price',
        'category',
        'quantity',
        'barcode',
        'tax',
        'weight_grams',
        'length_cm',
        'width_cm',
        'height_cm',
        'submitted_by',
        'agent',
    ];

    protected function casts(): array
    {
        return [
            'retail_price' => 'decimal:2',
            'cost_price' => 'decimal:2',
            'quantity' => 'integer',
            'weight_grams' => 'integer',
            'length_cm' => 'decimal:2',
            'width_cm' => 'decimal:2',
            'height_cm' => 'decimal:2',
        ];
    }

    public function isReadyForExport(): bool
    {
        return filled($this->sku)
            && $this->retail_price !== null
            && $this->quantity !== null
            && filled($this->tax);
    }
}
