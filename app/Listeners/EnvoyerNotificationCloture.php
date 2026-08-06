<?php

namespace App\Listeners;

use App\Events\ProjetCloture;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class EnvoyerNotificationCloture implements ShouldQueue
{
    public function __construct()
    {
        //
    }

    public function handle(ProjetCloture $event): void
    {
        Log::info(
            'Le projet '.$event->project->title.' a été clôturé.'
        );
    }
}
