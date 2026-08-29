<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

/* Generating Policies
Policies are classes that organize authorization logic around a particular model or resource. For example,
 if your application is a blog, you may have an App\Models\Post model and a corresponding App\Policies\PostPolicy
  to authorize user actions such as creating or updating posts.

You may generate a policy using the make:policy Artisan command. 
The generated policy will be placed in the app/Policies directory.
 If this directory does not exist in your application, Laravel will create it for you:

php artisan make:policy PostPolicy */

/* The make:policy command will generate an empty policy class.
 If you would like to generate a class with example policy
  methods related to viewing, creating, updating, and deleting
   the resource, you may provide a --model option when executing the command:

php artisan make:policy PostPolicy --model=Post */


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
        if ($project->trashed()) {
            return $project->isResponsable($user);
        }
        return $project->users()->where('user_id', $user->id)->exists();
    }

    /**
     * Créer un projet — tout utilisateur connecté peut créer un projet.
     */
    public function create(User $user): bool
    {
        // Un utilisateur ayant le rôle global "responsable" peut créer un projet.
        return $user->role === 'responsable';
    }

    /**
     * Modifier les informations du projet (titre, description, status) — Responsable seulement.
     */
    public function update(User $user, Project $project): bool
    {
        return !$project->trashed() && $project->isResponsable($user);
    }

    /**
     * Modifier l'avancement — Responsable ou Chercheur.
     */
    public function updateAvancement(User $user, Project $project): bool
    {
        return !$project->trashed() && ($project->isResponsable($user) || $project->isChercheur($user));
    }

    /**
     * Gérer les membres (ajouter / retirer) — Responsable seulement.
     */
    public function manageMember(User $user, Project $project): bool
    {
        return !$project->trashed() && $project->isResponsable($user);
    }

    /**
     * Archiver un projet — Responsable seulement.
     */
    public function delete(User $user, Project $project): bool
    {
        return !$project->trashed() && $project->isResponsable($user);
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