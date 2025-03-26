<?php

namespace Database\Seeders;

use App\Models\Transmission;
use Illuminate\Database\Seeder;

class TransmissionSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            ['title' => 'Механическая'],
            ['title' => 'Автоматическая'],
            ['title' => 'Роботизированная'],
            ['title' => 'Вариатор (CVT)'],
        ];
        foreach ($titles as $title) {
            Transmission::create($title);
        }
    }
}
