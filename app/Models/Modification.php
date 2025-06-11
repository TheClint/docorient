<?php

namespace App\Models;

use App\Models\Segment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Modification extends Model
{
    protected $fillable = [
        'texte',
        'segment_id',
    ];

    public function segment(): BelongsTo
    {
        return $this->belongsTo(Segment::class);
    }
}
