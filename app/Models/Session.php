<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Session extends Model
{
    protected $table = 'sessions_vote'; // fait le lien entre le modèle et la vraie table, car session est déjà utilisé par laravel

    protected $casts = [
        'ouverture' => 'datetime',
        'fermeture' => 'datetime',
        'commissaires' => 'array',
    ];

    protected $fillable = [
        'lieu',
        'nom',
        'user_id',
        'ouverture',
        'fermeture',
        'groupe_id',
    ];

    public function president(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enCours(): BelongsTo
    {
        return $this->belongsTo(Amendement::class);
    }

        public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class);
    }
}
