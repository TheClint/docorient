<?php

namespace App\Models;

use App\Models\Segment;
use App\Models\Amendement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modification extends Model
{
    protected $fillable = [
        'texte',
        'segment_id',
        'amendement_id',
    ];

    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }

    public function amendement(): BelongsTo
    {
        return $this->belongsTo(Amendement::class);
    }
}
