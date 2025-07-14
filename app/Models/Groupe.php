<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Groupe extends Model
{
    protected $fillable = [
        'nom',
        'primus_inter_pares',
    ];

    public function dirigeant(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function membres(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withTimestamps();
    }

    public function themes(): HasMany
    {
        return $this->hasMany(Theme::class);
    }

    public function getGroupe(): Groupe
    {
        return $this;
    }
}
