<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mandat extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id_from',
        'user_id_to',
        'groupe_id',
        'theme_id',
        'type',
        'fin_at',
    ];

    protected $dates = ['fin_at'];

    public function mandant()
    {
        return $this->belongsTo(User::class, 'user_id_from');
    }

    public function mandataire()
    {
        return $this->belongsTo(User::class, 'user_id_to');
    }

    public function groupe()
    {
        return $this->belongsTo(Groupe::class);
    }

    public function theme()
    {
        return $this->belongsTo(Theme::class);
    }

    public function setFinAtAttribute($value)
    {
        $this->attributes['fin_at'] = \Carbon\Carbon::parse($value)->endOfDay();
    }

}
