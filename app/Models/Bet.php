<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bet extends Model
{
    protected $table = 'bets';
    protected $fillable = ['user_id', 'lot_id', 'down_bet', 'up_bet'];

    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function Lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }
}
