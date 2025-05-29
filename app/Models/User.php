<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'role_id',
        'catalog_view',
        'name',
        'surname',
        'patronymic',
        'phone',
        'phone_verified_at',
        'email',
        'count_bets',
        'address',
        'password',
    ];

    public function CountBets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    public function Posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function Role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
