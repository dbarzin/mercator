<?php

namespace App\Http\Resources\Admin;

use App\Models\MApplication;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin MApplication
 */
class ApplicationResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var MApplication $app */
        $app = $this->resource;

        // Si tu veux partir de ce que renvoie le modèle par défaut
        $result = parent::toArray($request);

        // Puis on fusionne proprement les IDs des relations chargées
        return array_merge($result, [
            'entities'       => $this->whenLoaded('entities', fn () => $app->entities->pluck('id')->all()),
            'processes'      => $this->whenLoaded('processes', fn () => $app->processes->pluck('id')->all()),
            'services'       => $this->whenLoaded('services', fn () => $app->services->pluck('id')->all()),
            'databases'      => $this->whenLoaded('databases', fn () => $app->databases->pluck('id')->all()),
            'logicalServers' => $this->whenLoaded('logicalServers', fn () => $app->logicalServers->pluck('id')->all()),
            'activities'     => $this->whenLoaded('activities', fn () => $app->activities->pluck('id')->all()),
        ]);
    }
}
