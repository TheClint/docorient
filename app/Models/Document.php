<?php

namespace App\Models;

use App\Models\Segment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Document extends Model
{
    protected $fillable = [
        'nom',
        'description',
        'user_id',
    ];

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class);
    }
}
