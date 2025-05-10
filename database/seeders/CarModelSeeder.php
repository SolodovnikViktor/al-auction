<?php

namespace Database\Seeders;

use App\Models\CarModel;
use Illuminate\Database\Seeder;

class CarModelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $titles = [
            [
                'title' => '6',
                'brand_id' => '1'
            ],
            [
                'title' => 'S5',
                'brand_id' => '2'
            ],
        ];
        foreach ($titles as $title) {
            CarModel::create($title);
        }
    }
}
