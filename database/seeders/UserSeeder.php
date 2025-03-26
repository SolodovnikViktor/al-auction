<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'role' => 'admin',
            'name' => 'Виктор',
            'surname' => 'Солодовник',
            'patronymic' => 'Юрьевич',
            'phone' => '+79185160406',
            'email' => 'victordev@mail.ru',
            'address' => 'Адрес',
            'password' => Hash::make('14031988'),
        ]);

        User::factory()
            ->count(50)
            ->create();
    }
}
