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
        'amendement_fermeture',
        'vote_fermeture',
        'amendement_en_cours_id',
        'mode_vote',
        'theme_id',
    ];

    public function createur(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function segments(): HasMany
    {
        return $this->hasMany(Segment::class);
    }

    public function session()
    {
        return $this->belongsTo(Session::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function getGroupe(): ?Groupe
    {
        return $this->theme?->groupe;
    }
}

