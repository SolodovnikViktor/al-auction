<?php

namespace Database\Seeders;

use App\Models\Wheel;
use Illuminate\Database\Seeder;

class WheelSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            ['title' => 'Левый'],
            ['title' => 'Правый'],
        ];
        foreach ($titles as $title) {
            Wheel::create($title);
        }
    }
}
