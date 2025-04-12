<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Photo extends Model
{
    protected $table = 'photos';
    protected $fillable = [
        'post_id',
        'user_id',
        'name',
        'folder',
        'path',
        'path_min',
        'size',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getImageUrlAttribute(): string
    {
        return url($this->attributes['path']);
    }
}
