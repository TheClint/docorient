<?php

namespace App\Models;

use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Segment extends Model
{
    protected $fillable = [
        'texte',
        'document_id',
    ];

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }

    public function modifications(): BelongsToMany
    {
        return $this->belongsToMany(Amendement::class)->as('modifications');
    }
}
