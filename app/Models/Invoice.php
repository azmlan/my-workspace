<?php

namespace App\Models;

use App\Enums\InvoiceStatus;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'client_project_id',
        'invoice_number',
        'amount',
        'currency',
        'vat_rate',
        'status',
        'due_date',
        'paid_at',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'vat_rate' => 'decimal:2',
            'status' => InvoiceStatus::class,
            'due_date' => 'date',
            'paid_at' => 'datetime',
        ];
    }

    public function clientProject(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class);
    }

    public function isOverdue(): bool
    {
        return $this->due_date !== null
            && $this->due_date->isPast()
            && $this->status !== InvoiceStatus::Paid;
    }

    protected function vatAmount(): Attribute
    {
        return Attribute::make(
            get: fn () => round((float) $this->amount * (float) $this->vat_rate / 100, 2),
        );
    }

    protected function total(): Attribute
    {
        return Attribute::make(
            get: fn () => round((float) $this->amount + $this->vat_amount, 2),
        );
    }

    protected static function booted(): void
    {
        static::saving(function (Invoice $invoice) {
            if ($invoice->isDirty('status')) {
                $invoice->paid_at = $invoice->status === InvoiceStatus::Paid ? now() : null;
            }
        });
    }
}
