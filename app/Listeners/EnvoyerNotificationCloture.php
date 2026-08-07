<?php

namespace App\Listeners;

use App\Events\ProjetCloture;
use App\Jobs\GenerateProjectReport;
use App\Notifications\ProjetClotureNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class EnvoyerNotificationCloture implements ShouldQueue
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
     * Notifie tous les membres du projet clôturé et déclenche la génération du rapport.
     * Exécuté de façon asynchrone par le queue worker.
     */
    public function handle(ProjetCloture $event): void
    {
        $project = $event->project;

        Log::info("[Queue] EnvoyerNotificationCloture — Projet: «{$project->title}»");

        // Charger les membres si nécessaire
        $project->loadMissing('users');

        // 1. Notifier chaque membre du projet
        foreach ($project->users as $member) {
            $member->notify(new ProjetClotureNotification($project));
            Log::info("[Queue] Notification clôture envoyée à: {$member->name}");
        }
    }

    /**
     * Gestion de l'échec définitif du listener.
     */
    public function failed(ProjetCloture $event, \Throwable $exception): void
    {
        Log::error("[Queue] EnvoyerNotificationCloture — ÉCHEC pour «{$event->project->title}»: " . $exception->getMessage());
    }
}
