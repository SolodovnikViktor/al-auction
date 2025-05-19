<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            ['title' => 'Белый'],
            ['title' => 'Бежевый '],
            ['title' => 'Чёрный'],
            ['title' => 'Серый'],
            ['title' => 'Серебристый'],
            ['title' => 'Синий'],
            ['title' => 'Фиолетовый'],
            ['title' => 'Зелёный'],
            ['title' => 'Жёлтый'],
            ['title' => 'Оранжевый'],
            ['title' => 'Коричневый'],
            ['title' => 'Красный'],

        ];
        foreach ($titles as $title) {
            Color::create($title);
        }
    }
}
