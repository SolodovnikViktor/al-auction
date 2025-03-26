<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Image extends Model
{
    protected $table = 'images';
    protected $fillable = ['post_id', 'name', 'folder', 'path', 'size', 'pathMin'];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function getImageUrlAttribute(): string
    {
        return url($this->attributes['path']);
    }
}
