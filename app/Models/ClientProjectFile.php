<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientProjectFile extends Model
{
    protected $fillable = [
        'client_project_id',
        'original_name',
        'path',
        'mime_type',
        'size',
    ];

    public function clientProject(): BelongsTo
    {
        return $this->belongsTo(ClientProject::class);
    }

    public function formattedSize(): string
    {
        if ($this->size >= 1048576) {
            return number_format($this->size / 1048576, 1) . ' MB';
        }

        return number_format($this->size / 1024, 1) . ' KB';
    }

    public function extensionLabel(): string
    {
        return strtoupper(pathinfo($this->original_name, PATHINFO_EXTENSION));
    }

    public function extensionColor(): string
    {
        return match (strtolower(pathinfo($this->original_name, PATHINFO_EXTENSION))) {
            'pdf'           => 'bg-red-100 text-red-700',
            'docx', 'doc'  => 'bg-blue-100 text-blue-700',
            'xlsx', 'xls'  => 'bg-green-100 text-green-700',
            default         => 'bg-purple-100 text-purple-700',
        };
    }
}
