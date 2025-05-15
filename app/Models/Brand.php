<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Brand extends Model
{
    protected $table = 'brands';
    public $timestamps = false;

    protected $fillable = [
        'title',
    ];

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class)->select('title', 'id');
    }
}

