<?php

namespace App\Listeners;

use App\Events\MembreAjouteAuProjet;
use App\Notifications\MembreAjouteNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class EnvoyerNotificationMembre implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Nom de la queue utilisée pour ce listener.
     */
    public string $queue = 'notifications';

    /**
     * Nombre de tentatives avant abandon.
     */
    public int $tries = 3;

    /**
     * Délai en secondes entre les tentatives.
     */
    public int $backoff = 5;

    public function __construct() {}

    /**
     * Envoie une notification au nouveau membre ajouté au projet.
     * Exécuté de façon asynchrone par le queue worker.
     */
    public function handle(MembreAjouteAuProjet $event): void
    {
        Log::info("[Queue] EnvoyerNotificationMembre — Projet: «{$event->project->title}» — Membre: {$event->user->name}");

        $event->user->notify(
            new MembreAjouteNotification($event->project, $event->role)
        );
    }

    /**
     * Gestion de l'échec définitif du listener.
     */
    public function failed(MembreAjouteAuProjet $event, \Throwable $exception): void
    {
        Log::error("[Queue] EnvoyerNotificationMembre — ÉCHEC pour {$event->user->name}: " . $exception->getMessage());
    }
}
