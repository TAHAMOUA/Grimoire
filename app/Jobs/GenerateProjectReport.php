<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class GenerateProjectReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Nombre de tentatives en cas d'échec.
     */
    public int $tries = 3;

    /**
     * Délai (secondes) entre les tentatives.
     */
    public int $backoff = 10;

    public function __construct(
        public readonly Project $project
    ) {}

    /**
     * Génère un rapport texte du projet clôturé dans storage/app/reports/.
     */
    public function handle(): void
    {
        // Charger les membres si pas déjà chargés
        $this->project->loadMissing('users');

        $date     = now()->format('Y-m-d_H-i-s');
        $filename = "reports/rapport_projet_{$this->project->id}_{$date}.txt";

        $membres = $this->project->users->map(function ($user) {
            return "  - {$user->name} ({$user->email}) — rôle : {$user->pivot->role}";
        })->implode("\n");

        $content = <<<EOT
        ╔══════════════════════════════════════════════════════════════╗
        ║              RAPPORT DE CLÔTURE — GRIMOIRE                   ║
        ╚══════════════════════════════════════════════════════════════╝

        Projet       : {$this->project->title}
        Statut       : {$this->project->status}
        Avancement   : {$this->project->avancement}%
        Date clôture : {$date}

        ── Description ──────────────────────────────────────────────────
        {$this->project->description}

        ── Membres du projet ────────────────────────────────────────────
        {$membres}

        ── Généré automatiquement par Grimoire (Queue: database) ────────
        EOT;

        Storage::put($filename, $content);

        \Illuminate\Support\Facades\Log::info("[GenerateProjectReport] Rapport généré : {$filename}");
    }

    /**
     * Gestion de l'échec définitif du job.
     */
    public function failed(\Throwable $exception): void
    {
        \Illuminate\Support\Facades\Log::error(
            "[GenerateProjectReport] Échec pour le projet #{$this->project->id}: " . $exception->getMessage()
        );
    }
}
