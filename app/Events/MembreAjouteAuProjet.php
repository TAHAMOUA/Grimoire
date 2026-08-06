<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MembreAjouteAuProjet
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $project;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct($project, $user)
    {
        $this->project = $project;
        $this->user = $user;
    }
}
