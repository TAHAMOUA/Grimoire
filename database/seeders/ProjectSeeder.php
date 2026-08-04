<?php

namespace Database\Seeders;

use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Project::create([
            'title' => 'Projet IA',
            'description' => 'Développement d’un système intelligent.',
            'status' => 'encours',
            'avancement' => 20,
        ]);

        Project::create([
            'title' => 'Projet Web',
            'description' => 'Plateforme de gestion des projets.',
            'status' => 'encours',
            'avancement' => 50,
        ]);
    }
}