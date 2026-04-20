<?php

namespace App\Services\QueryEngine;

use Illuminate\Support\Collection;

readonly class GraphResult
{
    public function __construct(
        public Collection $nodes,
        public Collection $edges,
    ) {}

    public function toArray(): array
    {
        return [
            'nodes' => $this->nodes->values()->toArray(),
            'edges' => $this->edges->values()->toArray(),
        ];
    }

    public function nodeCount(): int
    {
        return $this->nodes->count();
    }

    public function edgeCount(): int
    {
        return $this->edges->count();
    }
}