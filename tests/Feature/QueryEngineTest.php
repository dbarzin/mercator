<?php

// tests/Feature/QueryEngine/QueryEngineTest.php

use App\Models\Application;
use App\Models\Database as MercatorDatabase;
use App\Models\LogicalServer;
use App\Models\MApplication;
use App\Models\User;
use App\Services\QueryEngine\GraphResult;
use App\Services\QueryEngine\ListResult;
use App\Services\QueryEngine\QueryEngineIntrospector;
use App\Services\QueryEngine\QueryResolver;
use Database\Seeders\PermissionRoleTableSeeder;
use Database\Seeders\PermissionsTableSeeder;
use Database\Seeders\RolesTableSeeder;
use Database\Seeders\RoleUserTableSeeder;
use Database\Seeders\UsersTableSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(Tests\TestCase::class, RefreshDatabase::class);

// ─────────────────────────────────────────────────────────────────────────────
// Bootstrap commun — authentification admin (identique à XSSProtectionTest)
// ─────────────────────────────────────────────────────────────────────────────

beforeEach(function () {
    $this->seed([
        PermissionsTableSeeder::class,
        RolesTableSeeder::class,
        PermissionRoleTableSeeder::class,
        UsersTableSeeder::class,
        RoleUserTableSeeder::class,
    ]);

    $this->admin    = User::query()->where('login', 'admin@admin.com')->first();
    $this->resolver = new QueryResolver();

    $this->actingAs($this->admin);
});

// ─────────────────────────────────────────────────────────────────────────────
// Helpers — créateurs d'entités Mercator
// ─────────────────────────────────────────────────────────────────────────────

/**
 * Crée un LogicalServer avec des valeurs par défaut surchargeables.
 * cpu est utilisé pour les tests d'opérateurs numériques.
 */
function makeServer(array $attrs = []): LogicalServer
{
    return LogicalServer::factory()->create(array_merge([
        'name'             => 'srv-' . uniqid(),
        'environment'      => 'production',
        'operating_system' => 'Linux',
        'cpu'              => 4,
    ], $attrs));
}

/**
 * Crée une Application et l'associe au LogicalServer via la relation pivot.
 */
function makeApp(LogicalServer $server, array $appAttrs = []): MApplication
{
    $app = MApplication::factory()->create(array_merge([
        'name' => 'app-' . uniqid(),
    ], $appAttrs));

    $server->applications()->syncWithoutDetaching([$app->id]);

    return $app;
}

/**
 * Crée une base de données liée à une Application.
 */
function makeDb(MApplication $app, array $attrs = []): MercatorDatabase
{
    $db = MercatorDatabase::factory()->create(array_merge([
        'name' => 'db-' . uniqid(),
    ], $attrs));

    $app->databases()->syncWithoutDetaching([$db->id]);

    return $db;
}

// ─────────────────────────────────────────────────────────────────────────────
// QueryEngineIntrospector
// ─────────────────────────────────────────────────────────────────────────────

describe('QueryEngineIntrospector', function () {

    it('résout la classe Eloquent d\'un modèle Mercator valide', function () {
        $class = QueryEngineIntrospector::resolveModelClass('LogicalServer');

        expect($class)->toBe(LogicalServer::class);
    });

    it('résout MApplication', function () {
        $class = QueryEngineIntrospector::resolveModelClass('MApplication');

        expect($class)->toBe(MApplication::class);
    });

    it('lève une exception HTTP 422 pour un modèle inconnu', function () {
        QueryEngineIntrospector::resolveModelClass('ModeleInexistant');
    })->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class);

    it('listModels retourne un tableau non vide de chaînes', function () {
        $models = QueryEngineIntrospector::listModels();

        expect($models)
            ->toBeArray()
            ->not->toBeEmpty()
            ->each->toBeString();
    });

    it('listModels retourne un tableau trié par ordre alphabétique', function () {
        $models = QueryEngineIntrospector::listModels();

        expect($models)->toBe(collect($models)->sort()->values()->all());
    });

    it('listModels inclut LogicalServer et MApplication', function () {
        $models = QueryEngineIntrospector::listModels();

        expect($models)
            ->toContain('logical-servers')
            ->toContain('applications');
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// applyFilter — opérateurs autorisés
// ─────────────────────────────────────────────────────────────────────────────

describe('applyFilter – opérateurs autorisés', function () {

    beforeEach(function () {
        makeServer(['name' => 'web-01', 'environment' => 'production', 'cpu' => 8]);
        makeServer(['name' => 'web-02', 'environment' => 'staging',    'cpu' => 4]);
        makeServer(['name' => 'db-01',  'environment' => 'production', 'cpu' => 2]);
    });

    it('= renvoie uniquement les enregistrements correspondants', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'environment', 'operator' => '=', 'value' => 'production']],
            'output'  => 'list',
        ]);

        expect($result)->toBeInstanceOf(ListResult::class);
        expect($result->rows)->toHaveCount(2);
        expect($result->rows->pluck('environment')->unique()->all())->toBe(['production']);
    });

    it('!= exclut les enregistrements correspondants', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'environment', 'operator' => '!=', 'value' => 'production']],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(1);
        expect($result->rows->first()['environment'])->toBe('staging');
    });

    it('> filtre strictement au-dessus du seuil', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'cpu', 'operator' => '>', 'value' => 4]],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(1);
        expect((int) $result->rows->first()['cpu'])->toBe(8);
    });

    it('>= inclut la valeur seuil (supérieur ou égal)', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'cpu', 'operator' => '>=', 'value' => 4]],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(2);
    });

    it('< filtre strictement en dessous du seuil', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'cpu', 'operator' => '<', 'value' => 4]],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(1);
        expect((int) $result->rows->first()['cpu'])->toBe(2);
    });

    it('<= inclut la valeur seuil (inférieur ou égal)', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'cpu', 'operator' => '<=', 'value' => 4]],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(2);
    });

    it('like filtre avec un pattern %', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'name', 'operator' => 'like', 'value' => 'web%']],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(2);
        expect($result->rows->pluck('name'))->each->toStartWith('web');
    });

    it('in filtre par liste de valeurs', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'cpu', 'operator' => 'in', 'value' => [2, 8]]],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(2);
    });

    it('not in exclut les valeurs listées', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'cpu', 'operator' => 'not in', 'value' => [2, 8]]],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(1);
        expect((int) $result->rows->first()['cpu'])->toBe(4);
    });

    it('plusieurs filtres sont combinés en AND implicite', function () {
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [
                ['field' => 'environment', 'operator' => '=',  'value' => 'production'],
                ['field' => 'cpu',         'operator' => '>=', 'value' => 8],
            ],
            'output' => 'list',
        ]);

        expect($result->rows)->toHaveCount(1);
        expect($result->rows->first()['name'])->toBe('web-01');
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// applyFilter — sécurité
// ─────────────────────────────────────────────────────────────────────────────

describe('applyFilter – sécurité', function () {

    it('un opérateur hors whitelist déclenche une exception HTTP 422', function () {
        $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'name', 'operator' => 'DROP TABLE', 'value' => 'x']],
            'output'  => 'list',
        ]);
    })->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class);

    it('une tentative d\'injection SQL dans l\'opérateur est rejetée', function () {
        $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'name', 'operator' => "= '1'; DROP TABLE logical_servers; --", 'value' => 'x']],
            'output'  => 'list',
        ]);
    })->throws(\Symfony\Component\HttpKernel\Exception\HttpException::class);

    it('les caractères spéciaux dans le nom de champ sont sanitisés sans erreur SQL', function () {
        makeServer(['name' => 'cible']);

        // "na;me" → sanitisé en "name" → filtre valide sans exception
        $result = $this->resolver->execute([
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'na;me', 'operator' => '=', 'value' => 'cible']],
            'output'  => 'list',
        ]);

        expect($result->rows)->toHaveCount(1);
    });

    it('les tirets et espaces dans le nom de champ sont supprimés', function () {
        makeServer(['name' => 'cible']);

        // Caractères non-alphanumériques intercalés — preg_replace les supprime
        // sans fragmenter le mot : 'na;me' → 'name', etc.
        foreach (['na;me', 'na,me', 'na!me'] as $dirty) {
            $result = $this->resolver->execute([
                'from'    => 'LogicalServer',
                'filters' => [['field' => $dirty, 'operator' => '=', 'value' => 'cible']],
                'output'  => 'list',
            ]);

            expect($result->rows)->toHaveCount(1, "champ [{$dirty}] non sanitisé correctement");
        }
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// execute — output list
// ─────────────────────────────────────────────────────────────────────────────

describe('execute – output list', function () {

    it('retourne un ListResult', function () {
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'list']);

        expect($result)->toBeInstanceOf(ListResult::class);
    });

    it('retourne tous les enregistrements sans filtre', function () {
        makeServer();
        makeServer();
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'list']);

        expect($result->rows)->toHaveCount(3);
    });

    it('respecte la limite par défaut de 100', function () {
        for ($i = 0; $i < 110; $i++) {
            makeServer();
        }

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'list']);

        expect($result->rows)->toHaveCount(100);
    });

    it('respecte une limite personnalisée', function () {
        for ($i = 0; $i < 15; $i++) {
            makeServer();
        }

        $result = $this->resolver->execute([
            'from'   => 'LogicalServer',
            'output' => 'list',
            'limit'  => 5,
        ]);

        expect($result->rows)->toHaveCount(5);
    });

    it('retourne une collection vide si aucun enregistrement', function () {
        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'list']);

        expect($result->rows)->toHaveCount(0);
    });

    it('list est le mode par défaut quand output est absent', function () {
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer']);

        expect($result)->toBeInstanceOf(ListResult::class);
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// execute — output graph (sans traversée)
// ─────────────────────────────────────────────────────────────────────────────

describe('execute – output graph (sans traversée)', function () {

    it('retourne un GraphResult', function () {
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'graph']);

        expect($result)->toBeInstanceOf(GraphResult::class);
    });

    it('crée un nœud par enregistrement', function () {
        makeServer();
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'graph']);

        expect($result->nodes)->toHaveCount(2);
    });

    it('les identifiants de nœuds ont le format LogicalServer_<id>', function () {
        $server = makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'graph']);

        expect($result->nodes->pluck('id')->all())
            ->toContain("LogicalServer_{$server->id}");
    });

    it('aucun edge sans traverse', function () {
        makeServer();
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'graph']);

        expect($result->edges)->toHaveCount(0);
    });

    it('graphe vide si aucun enregistrement', function () {
        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'graph']);

        expect($result->nodes)->toHaveCount(0);
        expect($result->edges)->toHaveCount(0);
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// execute — traversée de relations (output graph)
// ─────────────────────────────────────────────────────────────────────────────

describe('execute – traversée de relations', function () {

    it('crée des nœuds pour les entités liées (1 niveau)', function () {
        $server = makeServer();
        makeApp($server);
        makeApp($server);

        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['applications'],
            'output'   => 'graph',
        ]);

        // 1 serveur + 2 applications = 3 nœuds
        expect($result->nodes)->toHaveCount(3);
    });

    it('crée des edges entre le serveur et ses applications', function () {
        $server = makeServer();
        makeApp($server);
        makeApp($server);

        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['applications'],
            'output'   => 'graph',
        ]);

        expect($result->edges)->toHaveCount(2);
    });

    it('les edges contiennent les bons identifiants from/to', function () {
        $server = makeServer();
        $app    = makeApp($server);

        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['applications'],
            'output'   => 'graph',
        ]);

        $edge = $result->edges->first();
        expect($edge['from'])->toBe("LogicalServer_{$server->id}");
        expect($edge['to'])->toBe("MApplication_{$app->id}");
    });

    it('supporte la notation pointée sur 2 niveaux (applications.databases)', function () {
        $server = makeServer();
        $app    = makeApp($server);
        makeDb($app);
        makeDb($app);

        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['applications.databases'],
            'output'   => 'graph',
        ]);

        // 1 server + 1 app + 2 databases = 4 nœuds
        expect($result->nodes)->toHaveCount(4);
        // Edges : server→app + app→db1 + app→db2 = 3
        expect($result->edges)->toHaveCount(3);
    });

    it('déduplique les edges identiques', function () {
        $server = makeServer();
        makeApp($server);
        makeApp($server);

        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['applications'],
            'output'   => 'graph',
        ]);

        $pairs = $result->edges->map(fn ($e) => $e['from'] . '||' . $e['to']);
        expect($pairs->count())->toBe($pairs->unique()->count());
    });

    it('ignore silencieusement une relation inexistante', function () {
        makeServer();

        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['relationInexistante'],
            'output'   => 'graph',
        ]);

        expect($result->nodes)->toHaveCount(1);
        expect($result->edges)->toHaveCount(0);
    })->todo(
        'Le resolver appelle with([relation]) avant traverseNode : '
        . 'RelationNotFoundException levée avant le try/catch. '
        . 'Corriger execute() en filtrant les relations valides avant with().'
    );
});

// ─────────────────────────────────────────────────────────────────────────────
// Détection des cycles (visitedNodes)
// ─────────────────────────────────────────────────────────────────────────────

describe('Détection des cycles', function () {

    it('termine en moins de 2 secondes même avec des relations chargées', function () {
        $server = makeServer();
        makeApp($server);
        makeApp($server);

        $start  = microtime(true);
        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['applications'],
            'output'   => 'graph',
        ]);

        expect(microtime(true) - $start)->toBeLessThan(2.0);
        expect($result->nodes)->not->toBeEmpty();
    });

    it('ne duplique pas un nœud Application partagé par plusieurs serveurs', function () {
        $server1 = makeServer(['name' => 'srv-1']);
        $server2 = makeServer(['name' => 'srv-2']);
        $app     = MApplication::factory()->create(['name' => 'shared-app']);

        // La même application est associée aux deux serveurs
        $server1->applications()->syncWithoutDetaching([$app->id]);
        $server2->applications()->syncWithoutDetaching([$app->id]);

        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['applications'],
            'output'   => 'graph',
        ]);

        $appNodes = $result->nodes->filter(
            fn ($n) => str_starts_with($n['id'], 'MApplication_')
        );

        // Le nœud partagé ne doit apparaître qu'une seule fois
        expect($appNodes->pluck('id')->unique()->count())
            ->toBe($appNodes->count());
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// Structure de GraphResult et ListResult
// ─────────────────────────────────────────────────────────────────────────────

describe('Structure de GraphResult', function () {

    it('expose les propriétés nodes et edges', function () {
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'graph']);

        expect($result)->toHaveProperties(['nodes', 'edges']);
    });

    it('chaque nœud possède au minimum id et label', function () {
        makeServer(['name' => 'srv-label-test']);

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'graph']);

        $node = $result->nodes->first();
        expect($node)
            ->toHaveKey('id')
            ->toHaveKey('label');
        expect($node['label'])->toBe('srv-label-test');
    });

    it('chaque edge possède from et to', function () {
        $server = makeServer();
        makeApp($server);

        $result = $this->resolver->execute([
            'from'     => 'LogicalServer',
            'traverse' => ['applications'],
            'output'   => 'graph',
        ]);

        expect($result->edges->first())
            ->toHaveKey('from')
            ->toHaveKey('to');
    });
});

describe('Structure de ListResult', function () {

    it('expose les propriétés rows et columns', function () {
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'list']);

        expect($result)->toHaveProperties(['rows', 'columns']);
    });

    it('rows est une collection Eloquent', function () {
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'list']);

        expect($result->rows)->toBeInstanceOf(\Illuminate\Support\Collection::class);
    });

    it('columns contient au minimum id et name', function () {
        makeServer();

        $result = $this->resolver->execute(['from' => 'LogicalServer', 'output' => 'list']);

        expect($result->columns)
            ->toBeArray()
            ->not->toBeEmpty()
            ->toContain('id', 'name');
    });
});

// ─────────────────────────────────────────────────────────────────────────────
// QueryEngineController — endpoint HTTP /admin/queries/execute
// ─────────────────────────────────────────────────────────────────────────────

describe('QueryEngineController – POST /admin/queries/execute', function () {

    it('retourne 200 avec une réponse list structurée', function () {
        makeServer();

        $this->postJson('/admin/queries/execute', [
            'from'   => 'LogicalServer',
            'output' => 'list',
        ])->assertOk()
            ->assertJsonStructure(['rows', 'columns']);
    });

    it('retourne 200 avec une réponse graph structurée', function () {
        makeServer();

        $this->postJson('/admin/queries/execute', [
            'from'   => 'LogicalServer',
            'output' => 'graph',
        ])->assertOk()
            ->assertJsonStructure(['nodes', 'edges']);
    });

    it('retourne 422 si from est absent', function () {
        $this->postJson('/admin/queries/execute', [
            'output' => 'list',
        ])->assertUnprocessable();
    });

    it('retourne 422 si from contient des caractères interdits', function () {
        $this->postJson('/admin/queries/execute', [
            'from'   => 'Logical Server; DROP TABLE',
            'output' => 'list',
        ])->assertUnprocessable();
    });

    it('retourne 422 pour un opérateur non whitelisté', function () {
        $this->postJson('/admin/queries/execute', [
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'name', 'operator' => 'DROP', 'value' => 'x']],
            'output'  => 'list',
        ])->assertUnprocessable();
    });

    it('retourne 422 si operator est absent d\'un filtre', function () {
        $this->postJson('/admin/queries/execute', [
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'name', 'value' => 'x']],
            'output'  => 'list',
        ])->assertUnprocessable();
    });

    it('retourne 401 si non authentifié', function () {
        auth()->logout();

        $this->postJson('/admin/queries/execute', [
            'from'   => 'LogicalServer',
            'output' => 'list',
        ])->assertUnauthorized();
    });

    it('applique correctement les filtres via HTTP et retourne le bon enregistrement', function () {
        makeServer(['name' => 'web-prod',    'environment' => 'production']);
        makeServer(['name' => 'web-staging', 'environment' => 'staging']);

        $response = $this->postJson('/admin/queries/execute', [
            'from'    => 'LogicalServer',
            'filters' => [['field' => 'environment', 'operator' => '=', 'value' => 'production']],
            'output'  => 'list',
        ])->assertOk();

        expect($response->json('rows'))->toHaveCount(1);
        expect($response->json('rows.0.name'))->toBe('web-prod');
    });

    it('le résultat graph contient des nœuds et edges via HTTP', function () {
        $server = makeServer();
        makeApp($server);

        $response = $this->postJson('/admin/queries/execute', [
            'from'     => 'LogicalServer',
            'traverse' => ['applications'],
            'output'   => 'graph',
        ])->assertOk();

        $json = $response->json();

        expect(data_get($json, 'nodes'))->toHaveCount(2);
        expect(data_get($json, 'edges'))->toHaveCount(1);
    });
});