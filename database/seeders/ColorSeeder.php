<?php

namespace Database\Seeders;

use App\Models\Color;
use Illuminate\Database\Seeder;

class ColorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            ['title' => 'Белый'],
            ['title' => 'Чёрный'],
            ['title' => 'Серый'],
            ['title' => 'Серебристый'],
            ['title' => 'Синий'],
            ['title' => 'Красный'],
            ['title' => 'Зелёный'],
            ['title' => 'Жёлтый'],
            ['title' => 'Коричневый'],
        ];
        foreach ($titles as $title) {
            Color::create($title);
        }
    }
}
