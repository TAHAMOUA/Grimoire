<?php

namespace App\Listeners;

use App\Events\MembreAjouteAuProjet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EnvoyerNotificationMembre implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(MembreAjouteAuProjet $event): void
    {
        Log::info(
            'Le membre '.$event->user->name.
            ' a été ajouté au projet '.$event->project->title
        );
    }
}
