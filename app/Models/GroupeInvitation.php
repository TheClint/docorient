<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class GroupeInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'groupe_id', 'email', 'token', 'expires_at', 'used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used' => 'boolean',
    ];

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }
}

