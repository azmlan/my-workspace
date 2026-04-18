<?php

namespace App\Models;

use App\Enums\ClientProjectStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientProject extends Model
{
    protected $fillable = [
        'customer_id',
        'title',
        'status',
        'type',
        'description',
        'start_date',
        'deadline',
        'cancellation_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => ClientProjectStatus::class,
            'start_date' => 'date',
            'deadline' => 'date',
        ];
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function isOverdue(): bool
    {
        if (!$this->deadline) {
            return false;
        }

        if (in_array($this->status, [ClientProjectStatus::Completed, ClientProjectStatus::Cancelled])) {
            return false;
        }

        return $this->deadline->isPast();
    }
}
