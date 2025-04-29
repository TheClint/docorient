<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Amendement extends Model
{

    protected $fillable = [
        'texte',
        'commentaire',
        'user_id',
        'statut_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function statut(): BelongsTo
    {
        return $this->belongsTo(Statut::class);
    }

    public function approbations(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('approbation')->as('vote');
    }

    public function modifications(): BelongsToMany
    {
        return $this->belongsToMany(Segment::class)->as('modification');
    }

}
