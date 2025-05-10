<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            ['title' => 'Mazda'],
            ['title' => 'Omoda'],
        ];
        foreach ($titles as $title) {
            Brand::create($title);
        }
    }
}
