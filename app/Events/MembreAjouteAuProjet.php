<?php

namespace App\Events;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MembreAjouteAuProjet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Project $project;
    public User    $user;
    public string  $role;

    /**
     * @param Project $project Le projet auquel le membre a été ajouté.
     * @param User    $user    L'utilisateur ajouté.
     * @param string  $role    Le rôle attribué (chercheur, etudiant_assistant).
     */
    public function __construct(Project $project, User $user, string $role)
    {
        $this->project = $project;
        $this->user    = $user;
        $this->role    = $role;
    }
}
