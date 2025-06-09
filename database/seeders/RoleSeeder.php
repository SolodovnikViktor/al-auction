<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            [
                'title' => 'Администратор',
                'value' => 'admin',
            ],
            [
                'title' => 'Проверенный',
                'value' => 'trusted',
            ],
            [
                'title' => 'Гость',
                'value' => 'user',
            ],
        ];
        foreach ($titles as $title) {
            Role::create($title);
        }
    }
}
