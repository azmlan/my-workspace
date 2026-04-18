<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    const UPDATED_AT = null;

    protected $fillable = [
        'action',
        'subject_type',
        'subject_id',
        'subject_label',
        'meta',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'meta' => 'array',
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function log(
        string $action,
        string $subjectType,
        int $subjectId,
        string $subjectLabel,
        array $meta = [],
    ): self {
        return self::create([
            'action' => $action,
            'subject_type' => $subjectType,
            'subject_id' => $subjectId,
            'subject_label' => $subjectLabel,
            'meta' => $meta ?: null,
            'user_id' => auth()->id(),
        ]);
    }
}
