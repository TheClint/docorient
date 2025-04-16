<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Statut extends Model
{
    protected $fillable = [
        'libelle',
    ];

    public function amendements(): HasMany
    {
        return $this->hasMany(Segment::class);
    }
}
