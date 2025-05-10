<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property mixed $image_position
 */
class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $fillable = [
        'image_preview',
        'title',
        'vin',
        'brand_id',
        'car_model_id',
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
        'user_id',
        'is_published',
        'count_bets',
    ];

    public function color(): BelongsTo
    {
        return $this->belongsTo(Color::class)->select('title');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class)->select('title');
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(CarModel::class)->select('title');
    }

    public function body_type(): BelongsTo
    {
        return $this->belongsTo(BodyType::class)->select('title');
    }

    public function imagesPath(): HasMany
    {
        return $this->hasMany(Photo::class)->select(['id', 'path', 'post_id']);
    }

    public function images(): HasMany
    {
        return $this->hasMany(Photo::class);
    }

    public function transmission(): BelongsTo
    {
        return $this->belongsTo(Transmission::class)->select('title');
    }

    public function drive(): BelongsTo
    {
        return $this->belongsTo(Drive::class)->select('title');
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
