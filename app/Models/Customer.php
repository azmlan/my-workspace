<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'email',
        'source',
        'notes_general',
    ];

    public function clientProjects(): HasMany
    {
        return $this->hasMany(ClientProject::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
