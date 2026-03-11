<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'dispatcher@repair.local'],
            [
                'name' => 'Диспетчер Иванов',
                'password' => Hash::make('password'),
                'role' => User::ROLE_DISPATCHER,
            ]
        );

        User::updateOrCreate(
            ['email' => 'master1@repair.local'],
            [
                'name' => 'Мастер Петров',
                'password' => Hash::make('password'),
                'role' => User::ROLE_MASTER,
            ]
        );

        User::updateOrCreate(
            ['email' => 'master2@repair.local'],
            [
                'name' => 'Мастер Сидоров',
                'password' => Hash::make('password'),
                'role' => User::ROLE_MASTER,
            ]
        );
    }
}
