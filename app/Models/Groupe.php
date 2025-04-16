<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Groupe extends Model
{
    protected $fillable = [
        'nom',
    ];

    public function dirigeant(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function membres(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
