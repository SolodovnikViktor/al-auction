<?php

namespace Database\Seeders;

use App\Models\Fuel;
use Illuminate\Database\Seeder;

class FuelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            ['title' => 'Бензин'],
            ['title' => 'Дизель'],
            ['title' => 'Газ'],
            ['title' => 'Электричество'],
        ];
        foreach ($titles as $title) {
            Fuel::create($title);
        }
    }
}
