<?php

namespace App\Services;

use App\MApplication;

/**
 * Permet de réaliser des actions pour les évènements des applications.
 */
class EventService
{
    /**
     * Permet de charger les évènements d'une application
     */
    public function getLoadAppEvents(MApplication $application)
    {
        // Chargement des évènements triés
        $application->load(['events' => function ($query) {
            $query->orderBy('created_at', 'desc');
        },
        ]);

        // On veut le nom utilisateur pour chaque évènements
        foreach ($application->events as $event) {
            $event->load(['user' => function ($query) {
                $query->select('id', 'name');
            },
            ]);
        }
    }
}
