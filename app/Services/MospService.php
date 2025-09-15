<?php

// app/Services/MospService.php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class MospService
{
    public function __construct(
        private string $base = '',
        private int $ttl = 3600
    ) {
        $this->base = config('monarc.mosp_base', 'https://objects.monarc.lu/api/v2/object');
        $this->ttl = (int) config('monarc.cache_ttl', 3600);
    }

    /** Retourne l’index MOSP (liste des référentiels) */
    public function getIndex(): array
    {
        return Cache::remember('mosp:index', $this->ttl, function () {
            $resp = Http::timeout(30)->get($this->base /* .'/index.json' */);
            $resp->throw();

            return $resp->json();
        });
    }

    /** Liste des référentiels (uuid, title, slug, version) */
    public function getReferentials(): array
    {
        $idx = $this->getIndex();
        $refs = $idx['data'] ?? [];

        // Ajoute threat_count (lazy: on ne fetch pas tout de suite)
        foreach ($refs as &$r) {
            $r['threats_count'] = $this->getThreatsCountForReferential($r);
        }

        return $refs;
    }

    /** Détail d’un référentiel (incluant threats, vulnerabilities, recommendations) */
    public function getReferential(string $slug): array
    {
        $key = "mosp:ref:{$slug}";

        return Cache::remember($key, $this->ttl, function () use ($slug) {
            $resp = Http::timeout(30)->get($this->base."/referentials/{$slug}.json");
            $resp->throw();

            return $resp->json();
        });
    }

    /** Nombre de menaces par référentiel (compte rapide, mis en cache) */
    public function getThreatsCountForReferential(string $slug): int
    {
        dd($r['json_object']);

        return 0;
    }
}
