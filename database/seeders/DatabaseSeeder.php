<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            BodyTypeSeeder::class,
            DriveSeeder::class,
            TransmissionSeeder::class,
            ColorSeeder::class,
            BrandSeeder::class,
            CarModelSeeder::class,
            WheelSeeder::class,
            FuelSeeder::class,
            RoleSeeder::class,
            LotSeeder::class,
        ]);
    }
}
