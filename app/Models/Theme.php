<?php

namespace App\Models;

use App\Models\Groupe;
use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Theme extends Model
{
    protected $fillable = [
        'nom',
        'groupe_id',
    ];

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }
}
