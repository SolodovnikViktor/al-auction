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
            'role_id' => '1',
            'name' => 'Виктор',
            'surname' => 'Солодовник',
            'patronymic' => 'Юрьевич',
            'phone' => '+79185160406',
            'email' => 'victordev@mail.ru',
            'address' => 'Адрес',
            'password' => Hash::make('14031988'),
        ]);

        User::factory()->create([
            'role_id' => '1',
            'name' => 'Сергей',
            'surname' => 'Коршунов',
            'patronymic' => 'Викторович',
            'phone' => '+79185160407',
            'email' => 'admin@mail.ru',
            'address' => 'Адрес',
            'password' => Hash::make('14031988'),
        ]);

        User::factory()->create([
            'role_id' => '2',
            'name' => 'Вова',
            'surname' => 'Бережной',
            'patronymic' => 'Олегович',
            'phone' => '+79185160408',
            'email' => 'user@mail.ru',
            'address' => 'Адрес',
            'password' => Hash::make('14031988'),
        ]);
        User::factory()->create([
            'role_id' => '3',
            'name' => 'Женя',
            'surname' => 'Бобёр',
            'patronymic' => 'Андреевич',
            'phone' => '+79185160410',
            'email' => 'verifi@mail.ru',
            'address' => 'Адрес',
            'password' => Hash::make('14031988'),
        ]);

        User::factory()
            ->count(50)
            ->create();
    }
}
