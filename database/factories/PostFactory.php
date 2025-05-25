<?php

namespace Database\Factories;

use App\Models\BodyType;
use App\Models\Brand;
use App\Models\CarModel;
use App\Models\Color;
use App\Models\Drive;
use App\Models\Fuel;
use App\Models\Post;
use App\Models\Transmission;
use App\Models\Wheel;
use Illuminate\Database\Eloquent\Factories\Factory;


class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'brand_id' => Brand::all()->random()->id,
            'model_id' => CarModel::all()->random()->id,
            'vin' => fake()->regexify('[A-Z0-9]{17}'),
            'year_release' => fake()->year('-25 years', 'now'),
            'color_id' => Color::all()->random()->id,
            'mileage' => fake()->numberBetween(1000, 1000000),
            'fuel_id' => Fuel::all()->random()->id,
            'wheel_id' => Wheel::all()->random()->id,
            'drive_id' => Drive::all()->random()->id,
            'body_type_id' => BodyType::all()->random()->id,
            'transmission_id' => Transmission::all()->random()->id,
            'engine_capacity' => fake()->numberBetween(1, 10),
            'horsepower' => fake()->numberBetween(60, 1000),
            'price' => fake()->numberBetween(200000, 10000000),
            'description' => fake()->text,
            'user_id' => fake()->numberBetween(1, 2),
            'is_published' => fake()->numberBetween(0, 1),
            'count_bets' => fake()->numberBetween(0, 100),
        ];
    }
}
