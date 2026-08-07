<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'status',
        'avancement',
    ];

    /**
     * Boot the model to handle events.
     */
    protected static function booted()
    {
        static::deleted(function ($project) {
            // Le fait d'archiver (SoftDelete) déclenche le Job asynchrone
            \App\Jobs\GenerateProjectReport::dispatch($project)->onQueue('reports');

            // Et on déclenche l'événement pour la notification des membres
            $project->loadMissing('users');
            event(new \App\Events\ProjetCloture($project));
        });
    }

    /**
     * Les utilisateurs appartenant à ce projet (avec leur rôle dans le pivot).
     */
    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->withTimestamps();
    }

    /**
     * Retourne uniquement les responsables du projet.
     */
    public function responsables()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('role')
                    ->wherePivot('role', 'responsable');
    }

    /**
     * Retourne le rôle de l'utilisateur dans ce projet, ou null s'il n'est pas membre.
     */
    public function userRole(User $user): ?string
    {
        $pivot = $this->users()->where('user_id', $user->id)->first();
        return $pivot ? $pivot->pivot->role : null;
    }

    /**
     * Vérifie si l'utilisateur est responsable de ce projet.
     */
    public function isResponsable(User $user): bool
    {
        return $this->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'responsable')
            ->exists();
    }

    /**
     * Vérifie si l'utilisateur est chercheur dans ce projet.
     */
    public function isChercheur(User $user): bool
    {
        return $this->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'chercheur')
            ->exists();
    }

    /**
     * Vérifie si l'utilisateur est étudiant assistant dans ce projet.
     */
    public function isEtudiantAssistant(User $user): bool
    {
        return $this->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'etudiant_assistant')
            ->exists();
    }

    /**
     * Vérifie s'il resterait au moins un responsable si on retirait cet utilisateur.
     */
    public function hasOtherResponsable(User $user): bool
    {
        return $this->users()
            ->where('user_id', '!=', $user->id)
            ->wherePivot('role', 'responsable')
            ->exists();
    }
}