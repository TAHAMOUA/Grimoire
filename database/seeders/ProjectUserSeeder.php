<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectUserSeeder extends Seeder
{
    public function run(): void
    {
        $responsable = User::find(1);
        $chercheur = User::find(2);
        $etudiant = User::find(3);

        $projet = Project::find(1);

        $projet->users()->attach($responsable->id, [
            'role' => 'responsable'
        ]);

        $projet->users()->attach($chercheur->id, [
            'role' => 'chercheur'
        ]);

        $projet->users()->attach($etudiant->id, [
            'role' => 'etudiant_assistant'
        ]);
    }
}