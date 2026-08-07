<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FixRolesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->where('id', 2)->update(['role' => 'chercheur']);
        DB::table('users')->where('id', 3)->update(['role' => 'etudiant_assistant']);
        echo "Roles mis à jour.\n";
    }
}
