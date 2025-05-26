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
        'session_id',
        'user_id',
        'amendement_ouverture',
        'vote_fermeture',
        'amendement_en_cours_id',
        'mode_vote',
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

