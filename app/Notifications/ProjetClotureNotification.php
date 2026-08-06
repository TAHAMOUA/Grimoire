<?php

namespace App\Notifications;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ProjetClotureNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Project $project
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
            ->subject("Projet archivé : « {$this->project->title} »")
            ->greeting("Bonjour {$notifiable->name},")
            ->line("Le projet **{$this->project->title}** a été archivé.")
            ->line("Avancement final : **{$this->project->avancement}%**.")
            ->line("Un rapport de clôture a été généré automatiquement.")
            ->action('Voir les projets archivés', url('/projects/archived'))
            ->line("Merci d'avoir contribué à ce projet sur Grimoire !");
    }

    /**
     * Données stockées en base (canal database).
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'type'          => 'projet_cloture',
            'project_id'    => $this->project->id,
            'project_title' => $this->project->title,
            'avancement'    => $this->project->avancement,
            'message'       => "Le projet « {$this->project->title} » a été archivé (avancement : {$this->project->avancement}%).",
        ];
    }
}
