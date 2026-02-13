<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Mercator\Core\Models\Subnetwork;
use Symfony\Component\HttpFoundation\Response;

class ExplorerController2 extends Controller
{
    /**
     * Affiche la vue principale de l'explorer
     */
    public function explore(Request $request): View
    {
        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin/reports/explore');
    }

    /**
     * API endpoint pour récupérer les nodes avec filtrage optionnel
     */
    public function getNodes(Request $request): JsonResponse
    {
        \Log::info('getNodes', ['request' => $request->all()]);

        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'filter' => 'sometimes|array',
            'filter.*' => 'integer|between:1,9',
            'search' => 'sometimes|string|max:255',
        ]);

        $filters = $request->input('filter', []);
        $search = $request->input('search');

        // Cache key basé sur les filtres
        $cacheKey = 'explorer_nodes_' . md5(json_encode($filters) . $search);

        $nodes = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($filters, $search) {
            return $this->buildNodes($filters, $search);
        });

        return response()->json([
            'success' => true,
            'data' => $nodes,
            'count' => count($nodes),
        ]);
    }

    /**
     * API endpoint pour récupérer les edges d'un ou plusieurs nodes
     */
    public function getEdges(Request $request): JsonResponse
    {
        \Log::info('getEdges', ['request' => $request->all()]);

        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'node_ids' => 'required|array',
            'node_ids.*' => 'string',
            'depth' => 'sometimes|integer|min:1|max:5',
        ]);

        $nodeIds = $request->input('node_ids', []);
        $depth = $request->input('depth', 1);

        $edges = $this->buildEdgesForNodes($nodeIds, $depth);

        return response()->json([
            'success' => true,
            'data' => $edges,
            'count' => count($edges),
        ]);
    }

    /**
     * API endpoint pour récupérer un graphe complet autour d'un node
     */
    public function getNodeGraph(Request $request, string $nodeId): JsonResponse
    {
        \Log::info('getNodeGraph ', [$nodeId]);

        abort_if(Gate::denies('explore_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'depth' => 'sometimes|integer|min:1|max:5',
            'filter' => 'sometimes|array',
        ]);

        $depth = $request->input('depth', 1);
        $filters = $request->input('filter', []);

        $result = $this->buildGraphFromNode($nodeId, $depth, $filters);

        \Log::info('getNodeGraph result', [$result]);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Construit la liste des nodes selon les filtres
     */
    private function buildNodes(array $filters = [], ?string $search = null): array
    {
        $nodes = [];
        $subnetworks = Subnetwork::all();

        // Si aucun filtre, charger uniquement un subset pour performance
        if (empty($filters)) {
            $filters = [1, 2, 3, 4, 5, 6, 7]; // Par défaut : tout
        }

        // Physical view - 6
        if (in_array(6, $filters)) {
            $nodes = array_merge($nodes, $this->getPhysicalViewNodes($search));
        }

        // Network infrastructure - 7
        if (in_array(7, $filters)) {
            $nodes = array_merge($nodes, $this->getNetworkViewNodes($search, $subnetworks));
        }

        // Logical infrastructure - 5
        if (in_array(5, $filters)) {
            $nodes = array_merge($nodes, $this->getLogicalViewNodes($search));
        }

        // Administration - 4
        if (in_array(4, $filters)) {
            $nodes = array_merge($nodes, $this->getAdministrationViewNodes($search));
        }

        // Applications - 3
        if (in_array(3, $filters)) {
            $nodes = array_merge($nodes, $this->getApplicationsViewNodes($search));
        }

        // Information System - 2
        if (in_array(2, $filters)) {
            $nodes = array_merge($nodes, $this->getInformationSystemViewNodes($search));
        }

        // Ecosystem - 1
        if (in_array(1, $filters)) {
            $nodes = array_merge($nodes, $this->getEcosystemViewNodes($search));
        }

        return $nodes;
    }

    /**
     * Construit le graphe autour d'un node avec profondeur
     */
    private function buildGraphFromNode(string $nodeId, int $depth, array $filters = []): array
    {
        \Log::info('buildGraphFromNode', [$nodeId, $depth, $filters]);

        $nodes = [];
        $edges = [];
        $visited = [];

        $this->traverseGraph($nodeId, $depth, $filters, $nodes, $edges, $visited);

        return [
            'nodes' => array_values($nodes),
            'edges' => $edges,
        ];
    }

    /**
     * Traverse récursif du graphe
     */
    private function traverseGraph(string $nodeId, int $depth, array $filters, array &$nodes, array &$edges, array &$visited): void
    {
        \Log::info('traverseGraph', [$nodeId, $depth, $filters]);

        if ($depth <= 0 || in_array($nodeId, $visited)) {
            return;
        }

        $visited[] = $nodeId;

        // Récupérer les edges de ce node
        $nodeEdges = $this->getEdgesForSingleNode($nodeId);

        foreach ($nodeEdges as $edge) {
            // Ajouter l'edge
            $edgeKey = $edge['from'] . '-' . $edge['to'] . '-' . ($edge['name'] ?? '');
            if (!isset($edges[$edgeKey])) {
                $edges[$edgeKey] = $edge;
            }

            // Récupérer le node connecté
            $connectedNodeId = ($edge['from'] === $nodeId) ? $edge['to'] : $edge['from'];

            if (!isset($nodes[$connectedNodeId])) {
                $connectedNode = $this->getNodeById($connectedNodeId);
                if ($connectedNode) {
                    // Vérifier les filtres
                    if (empty($filters) || in_array($connectedNode['vue'], $filters)) {
                        $nodes[$connectedNodeId] = $connectedNode;

                        // Continuer la traversée
                        $this->traverseGraph($connectedNodeId, $depth - 1, $filters, $nodes, $edges, $visited);
                    }
                }
            }
        }
    }

    /**
     * Récupère un node par son ID
     */
    private function getNodeById(string $nodeId): ?array
    {
        // Parser l'ID pour extraire le type et l'ID réel
        if (preg_match('/^([A-Z_]+)_(\d+)$/', $nodeId, $matches)) {
            $type = $matches[1];
            $id = $matches[2];

            $typeMapping = [
                'SITE' => ['table' => 'sites', 'vue' => 6, 'image' => '/images/site.png', 'route' => 'sites'],
                'BUILDING' => ['table' => 'buildings', 'vue' => 6, 'image' => '/images/building.png', 'route' => 'buildings'],
                'BAY' => ['table' => 'bays', 'vue' => 6, 'image' => '/images/bay.png', 'route' => 'bays'],
                'PSERVER' => ['table' => 'physical_servers', 'vue' => 6, 'image' => '/images/server.png', 'route' => 'physical-servers'],
                'WORK' => ['table' => 'workstations', 'vue' => 6, 'image' => '/images/workstation.png', 'route' => 'workstations'],
                'APP' => ['table' => 'm_applications', 'vue' => 3, 'image' => '/images/application.png', 'route' => 'm-applications'],
                'DATABASE' => ['table' => 'databases', 'vue' => 3, 'image' => '/images/database.png', 'route' => 'databases'],
                'PROCESS' => ['table' => 'processes', 'vue' => 2, 'image' => '/images/process.png', 'route' => 'processes'],
                'ENTITY' => ['table' => 'entities', 'vue' => 1, 'image' => '/images/entity.png', 'route' => 'entities'],
                // Ajouter tous les autres types...
            ];

            if (isset($typeMapping[$type])) {
                $config = $typeMapping[$type];
                $record = DB::table($config['table'])
                    ->select('id', 'name', 'icon_id')
                    ->where('id', $id)
                    ->whereNull('deleted_at')
                    ->first();

                if ($record) {
                    return [
                        'id' => $nodeId,
                        'vue' => $config['vue'],
                        'label' => $record->name,
                        'image' => $record->icon_id ? "/admin/documents/{$record->icon_id}" : $config['image'],
                        'type' => $config['route'],
                    ];
                }
            }
        }

        return null;
    }

    /**
     * Récupère les edges pour un node spécifique
     */
    private function getEdgesForSingleNode(string $nodeId): array
    {
        $edges = [];

        \Log::info('getEdgesForSingleNode', [$nodeId]);

        // Parser le nodeId pour déterminer le type
        if (preg_match('/^([A-Z_]+)_(\d+)$/', $nodeId, $matches)) {
            $type = $matches[1];
            $id = $matches[2];

            // Logique spécifique selon le type
            // Exemple pour un BUILDING
            if ($type === 'BUILDING') {
                $building = DB::table('buildings')
                    ->select('building_id', 'site_id')
                    ->where('id', $id)
                    ->whereNull('deleted_at')
                    ->first();

                if ($building) {
                    if ($building->building_id) {
                        $edges[] = $this->createLinkEdge($nodeId, "BUILDING_{$building->building_id}");
                    } elseif ($building->site_id) {
                        $edges[] = $this->createLinkEdge($nodeId, "SITE_{$building->site_id}");
                    }
                }
            }
            // Ajouter la logique pour tous les autres types...
        }

        \Log::info('getEdgesForSingleNode result', [$edges]);
        return $edges;
    }

    /**
     * Construit les edges pour plusieurs nodes
     */
    private function buildEdgesForNodes(array $nodeIds, int $depth = 1): array
    {
        $edges = [];

        foreach ($nodeIds as $nodeId) {
            $nodeEdges = $this->getEdgesForSingleNode($nodeId);
            $edges = array_merge($edges, $nodeEdges);
        }

        // Dédupliquer
        return array_values(array_unique($edges, SORT_REGULAR));
    }

    // ===== Méthodes pour construire les différentes vues =====

    private function getPhysicalViewNodes(?string $search): array
    {
        $nodes = [];

        // Sites
        $query = DB::table('sites')->select('id', 'name', 'icon_id')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $site) {
            $nodes[] = [
                'id' => "SITE_{$site->id}",
                'vue' => 6,
                'label' => $site->name,
                'image' => $site->icon_id ? "/admin/documents/{$site->icon_id}" : '/images/site.png',
                'type' => 'sites',
            ];
        }

        // Buildings
        $query = DB::table('buildings')->select('id', 'name', 'icon_id')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $building) {
            $nodes[] = [
                'id' => "BUILDING_{$building->id}",
                'vue' => 6,
                'label' => $building->name,
                'image' => $building->icon_id ? "/admin/documents/{$building->icon_id}" : '/images/building.png',
                'type' => 'buildings',
            ];
        }

        // Ajouter les autres entités physiques (bays, servers, workstations, etc.)

        return $nodes;
    }

    private function getNetworkViewNodes(?string $search, $subnetworks): array
    {
        $nodes = [];

        // Wan, Man, Lan, Vlan, Subnetworks, etc.
        $query = DB::table('wans')->select('id', 'name')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $wan) {
            $nodes[] = [
                'id' => "WAN_{$wan->id}",
                'vue' => 7,
                'label' => $wan->name,
                'image' => '/images/wan.png',
                'type' => 'wans',
            ];
        }

        // Ajouter Man, Lan, Vlan, Subnetworks, etc.

        return $nodes;
    }

    private function getLogicalViewNodes(?string $search): array
    {
        $nodes = [];

        // Servers, logical servers, certificates, etc.
        $query = DB::table('logical_servers')->select('id', 'name', 'icon_id')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $server) {
            $nodes[] = [
                'id' => "LSERVER_{$server->id}",
                'vue' => 5,
                'label' => $server->name,
                'image' => $server->icon_id ? "/admin/documents/{$server->icon_id}" : '/images/server.png',
                'type' => 'logical-servers',
            ];
        }

        return $nodes;
    }

    private function getAdministrationViewNodes(?string $search): array
    {
        $nodes = [];

        // Annuaires, domaines AD, forêts, etc.
        $query = DB::table('annuaires')->select('id', 'name')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $annuaire) {
            $nodes[] = [
                'id' => "ANNUAIRE_{$annuaire->id}",
                'vue' => 4,
                'label' => $annuaire->name,
                'image' => '/images/annuaire.png',
                'type' => 'annuaires',
            ];
        }

        return $nodes;
    }

    private function getApplicationsViewNodes(?string $search): array
    {
        $nodes = [];

        // Applications, databases
        $query = DB::table('m_applications')->select('id', 'name', 'icon_id')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $app) {
            $nodes[] = [
                'id' => "APP_{$app->id}",
                'vue' => 3,
                'label' => $app->name,
                'image' => $app->icon_id ? "/admin/documents/{$app->icon_id}" : '/images/application.png',
                'type' => 'm-applications',
            ];
        }

        // Databases
        $query = DB::table('databases')->select('id', 'name')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $database) {
            $nodes[] = [
                'id' => "DATABASE_{$database->id}",
                'vue' => 3,
                'label' => $database->name,
                'image' => '/images/database.png',
                'type' => 'databases',
            ];
        }

        return $nodes;
    }

    private function getInformationSystemViewNodes(?string $search): array
    {
        $nodes = [];

        // Processes, information, activities, etc.
        $query = DB::table('processes')->select('id', 'name', 'icon_id')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $process) {
            $nodes[] = [
                'id' => "PROCESS_{$process->id}",
                'vue' => 2,
                'label' => $process->name,
                'image' => $process->icon_id ? "/admin/documents/{$process->icon_id}" : '/images/process.png',
                'type' => 'processes',
            ];
        }

        return $nodes;
    }

    private function getEcosystemViewNodes(?string $search): array
    {
        $nodes = [];

        // Entities, relations
        $query = DB::table('entities')->select('id', 'name', 'icon_id')->whereNull('deleted_at');
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        foreach ($query->get() as $entity) {
            $nodes[] = [
                'id' => "ENTITY_{$entity->id}",
                'vue' => 1,
                'label' => $entity->name,
                'image' => $entity->icon_id ? "/admin/documents/{$entity->icon_id}" : '/images/entity.png',
                'type' => 'entities',
            ];
        }

        return $nodes;
    }

    // ===== Méthodes utilitaires pour créer des edges =====

    private function createLinkEdge(string $from, string $to): array
    {
        return [
            'name' => null,
            'bidirectional' => false,
            'from' => $from,
            'to' => $to,
            'type' => 'LINK',
        ];
    }

    private function createFluxEdge(string $from, string $to, ?string $name = null, bool $bidirectional = false): array
    {
        return [
            'name' => $name,
            'bidirectional' => $bidirectional,
            'from' => $from,
            'to' => $to,
            'type' => 'FLUX',
        ];
    }

    private function createCableEdge(string $from, string $to): array
    {
        return [
            'name' => null,
            'bidirectional' => false,
            'from' => $from,
            'to' => $to,
            'type' => 'CABLE',
        ];
    }
}
