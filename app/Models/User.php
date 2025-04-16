<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Document;
use App\Models\Amendement;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'groupe_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function amendements(): HasMany
    {
        return $this->hasMany(Amendement::class);
    }

    public function groupeDirige(): HasOne
    {
        return $this->hasOne(Groupe::class);
    }

    public function groupe(): BelongsTo
    {
        return $this->belongsTo(Groupe::class);
    }

    public function approbations(): BelongsToMany
    {
        return $this->belongsToMany(Amendement::class)->withPivot('approbation')->as('vote');;
    }
}
