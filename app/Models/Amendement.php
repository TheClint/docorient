<?php

namespace App\Models;

use App\Models\User;
use App\Models\Modification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amendement extends Model
{

    protected $fillable = [
        'texte',
        'commentaire',
        'user_id',
        'statut_id',
        'vote_fermeture',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statut(): BelongsTo
    {
        return $this->belongsTo(Statut::class);
    }

    public function votes(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('approbation')
                    ->as('vote')
                    ->withTimestamps();
    }

    public function propositions(): BelongsToMany
    {
        return $this->belongsToMany(Segment::class)->as('proposition');
    }

    public function modifications() : HasMany
    {
        return $this->hasMany(Modification::class);
    }

    // test si un amendement est un amendement de fusion ou non
    public function estFusion(): bool
    {
        $estFusion = false;

        $segment = $this->propositions[0];
        $document = $segment?->document;
        
        if($document->session_id === null){
            if($document->vote_fermeture < $this->created_at)
            $estFusion = true;
        }else{
            if($document->session->ouverture < $this->created_at)
            $estFusion = true;
        }

        return $estFusion;
    }

}
