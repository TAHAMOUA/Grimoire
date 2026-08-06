<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    /**
     * Afficher la liste des projets.
     * Tout utilisateur connecté peut voir la liste (filtrée par ses projets dans le controller).
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Voir un projet — uniquement si l'utilisateur est membre.
     */
    public function view(User $user, Project $project): bool
    {
        return $project->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Créer un projet — tout utilisateur connecté peut créer un projet.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Modifier les informations du projet (titre, description, status) — Responsable seulement.
     */
    public function update(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Modifier l'avancement — Responsable ou Chercheur.
     */
    public function updateAvancement(User $user, Project $project): bool
    {
        return $project->isResponsable($user) || $project->isChercheur($user);
    }

    /**
     * Gérer les membres (ajouter / retirer) — Responsable seulement.
     */
    public function manageMember(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Archiver un projet — Responsable seulement.
     */
    public function delete(User $user, Project $project): bool
    {
        return $project->isResponsable($user);
    }

    /**
     * Voir les projets archivés — Responsable seulement.
     */
    public function viewArchived(User $user): bool
    {
        return true; // filtré dans le controller pour ne voir que les siens
    }

    /**
     * Restaurer un projet — désactivé.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Suppression définitive — désactivé.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}