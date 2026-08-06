<?php

namespace App\Providers;

use App\Events\MembreAjouteAuProjet;
use App\Events\ProjetCloture;
use App\Listeners\EnvoyerNotificationMembre;
use App\Listeners\EnvoyerNotificationCloture;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        MembreAjouteAuProjet::class => [
            EnvoyerNotificationMembre::class,
        ],

        ProjetCloture::class => [
            EnvoyerNotificationCloture::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
