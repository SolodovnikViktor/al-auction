<?php

namespace Database\Seeders;

use App\Models\BodyType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BodyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            ['title' => 'Седан'],
            ['title' => 'Купе'],
            ['title' => 'Хэтчбек'],
            ['title' => 'Кроссовер'],
            ['title' => 'Пикап'],
            ['title' => 'Лифтбек'],
            ['title' => 'Универсал'],
            ['title' => 'Кабриолет'],
            ['title' => 'Фургон'],
            ['title' => 'Минивэн'],
        ];
        foreach ($titles as $title) {
            BodyType::create($title);
        }
    }
}
