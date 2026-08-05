<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    private function getRole(User $user, Project $project): ?string
{
    $member = $project->users()
        ->where('users.id', $user->id)
        ->first();

    return $member?->pivot?->role;
}
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Project $project): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Project $project): bool
    {
        return $this->getRole($user, $project) === 'responsable';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Project $project): bool
    {
        return $this->getRole($user, $project) === 'responsable';
    }
    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return $this->getRole($user, $project) === 'responsable';
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
    public function removeMember(User $user, Project $project): bool
    {
        return $this->getRole($user, $project) === 'responsable';
    }
    public function updateProgress(User $user, Project $project): bool
    {
        return in_array(
            $this->getRole($user, $project),
            ['responsable', 'chercheur']
        );
    }
}
