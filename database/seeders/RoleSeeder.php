<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $titles = [
            ['title' => 'Admin'],
            ['title' => 'User'],
            ['title' => 'Trusted '],
        ];
        foreach ($titles as $title) {
            Role::create($title);
        }
    }
}
