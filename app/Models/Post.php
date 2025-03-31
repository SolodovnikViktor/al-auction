<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'imagePosition',
        'title',
        'vin',
        'brand',
        'model',
        'year_release',
        'color_id',
        'mileage',
        'fuel',
        'drive_id',
        'body_type_id',
        'transmission_id',
        'engine_capacity',
        'horsepower',
        'price',
        'up_price',
        'description',
        'preview_image',
        'user_id',
        'is_published',
    ];

    public function imagesPath(): HasMany
    {
        return $this->hasMany(Image::class)->select(['id', 'path', 'post_id']);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Image::class);
    }

    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
//        return $this->BelongsTo(User::class)->withDefault([
//            'name' => 'Guest Author',
//        ]);
    }
}
