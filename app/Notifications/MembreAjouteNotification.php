<?php

namespace App\Notifications;

use App\Models\Project;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembreAjouteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Project $project,
        public readonly string  $role
    ) {}

    /**
     * Canaux de notification : mail (log en local) + base de données.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Contenu du mail.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Vous avez été ajouté au projet « {$this->project->title} »")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Vous avez été ajouté au projet **{$this->project->title}** avec le rôle : **{$this->role}**.")
            ->line("Vous pouvez dès maintenant consulter les détails du projet.")
            ->action('Voir le projet', url("/projects/{$this->project->id}"))
            ->line("Merci d'utiliser Grimoire !");
    }

    /**
     * Données stockées en base (canal database).
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'        => 'membre_ajoute',
            'project_id'  => $this->project->id,
            'project_title' => $this->project->title,
            'role'        => $this->role,
            'message'     => "Vous avez été ajouté au projet « {$this->project->title} » (rôle : {$this->role}).",
        ];
    }
}
