<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Afficher la liste des projets.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Voir un projet.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Créer un projet.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Modifier un projet.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'responsable')
            ->exists();
    }

    /**
     * Archiver un projet.
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'responsable')
            ->exists();
    }

    /**
     * Restaurer un projet.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Suppression définitive.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}