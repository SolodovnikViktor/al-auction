<?php

namespace Database\Seeders;

use App\Models\Drive;
use Illuminate\Database\Seeder;

class DriveSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            ['title' => 'Передний'],
            ['title' => 'Задний'],
            ['title' => 'Полный'],
        ];
        foreach ($titles as $title) {
            Drive::create($title);
        }
    }
}
