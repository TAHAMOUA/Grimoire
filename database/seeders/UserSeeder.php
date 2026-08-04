<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Responsable',
            'email' => 'responsable@test.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Chercheur',
            'email' => 'chercheur@test.com',
            'password' => Hash::make('password'),
        ]);

        User::create([
            'name' => 'Etudiant',
            'email' => 'etudiant@test.com',
            'password' => Hash::make('password'),
        ]);
    }
}