<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    protected $table = 'photos';
    protected $fillable = [
        'lot_id',
        'user_id',
        'name',
        'folder',
        'path',
        'path_min',
        'size',
    ];

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class);
    }

    public function getPhotoUrlAttribute(): string
    {
        return url($this->attributes['path']);
    }

    public function getPhotoMinUrlAttribute(): string
    {
        return url($this->attributes['path_min']);
    }
}
