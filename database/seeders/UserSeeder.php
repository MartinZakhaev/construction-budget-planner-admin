<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'email' => 'owner@terra.corp',
                'full_name' => 'Terra Owner',
                'password' => Hash::make('secret123'),
            ],
            [
                'email' => 'collaborator@terra.corp',
                'full_name' => 'Project Collaborator',
                'password' => Hash::make('secret123'),
            ],
            [
                'email' => 'auditor@terra.corp',
                'full_name' => 'Audit Reviewer',
                'password' => Hash::make('secret123'),
            ],
        ];

        foreach ($users as $attributes) {
            User::query()->updateOrCreate(
                ['email' => $attributes['email']],
                $attributes,
            );
        }
    }
}
