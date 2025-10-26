<?php

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Helpers
 */
function actAsAdminAllAllowed(): User {
    // Récupère (ou crée) un user "admin" et bypass toutes les autorisations
    $user = User::find(1) ?? User::factory()->create(['id' => 1]);
    Passport::actingAs($user);

    // Bypass complet des Gates/Policies pendant ce test
    Gate::before(function ($user, $ability) {
        return true; // autorise tout
    });

    return $user;
}

/**
 * Valide une réponse de téléchargement de fichier (BinaryFileResponse),
 * l'extension (via Content-Disposition) et un contenu non vide.
 * Pour DOCX/XLSX on vérifie aussi la signature ZIP "PK".
 */
function assertBinaryDownload($response, string $expectedExt, int $minBytes = 100): void {
    $response->assertOk();
    $response->assertHeader('Content-Disposition');

    $disposition = $response->headers->get('Content-Disposition');
    expect($disposition)->toBeString()
        ->and(strtolower($disposition))->toContain('attachment;')
        ->and(strtolower($disposition))->toContain('filename=')
        ->and(strtolower($disposition))->toContain('.' . strtolower($expectedExt));

    $base = $response->baseResponse;
    expect($base)->toBeInstanceOf(BinaryFileResponse::class);

    $path = $base->getFile()->getPathname();
    expect(is_file($path))->toBeTrue();

    $content = file_get_contents($path);
    expect(strlen($content))->toBeGreaterThan($minBytes);

    // DOCX/XLSX sont des ZIP → début "PK"
    if (in_array(strtolower($expectedExt), ['docx', 'xlsx'], true)) {
        expect(substr($content, 0, 2))->toBe('PK');
    }
}

/**
 * DATASETS
 * - FILE_REPORTS : routes qui renvoient un fichier + extension attendue
 * - JSON_REPORTS : routes JSON
 */
$FILE_REPORTS = [
    // endpoint                               ext
    ['/api/report/cartography',              'docx'],
    ['/api/report/entities',                 'xlsx'],
    ['/api/report/applicationsByBlocks',     'xlsx'],
    ['/api/report/directory',                'docx'],
    ['/api/report/logicalServers',           'xlsx'],
    ['/api/report/securityNeeds',            'xlsx'],
    ['/api/report/logicalServerConfigs',     'xlsx'],
    ['/api/report/externalAccess',           'xlsx'],
    ['/api/report/physicalInventory',        'xlsx'],
    ['/api/report/vlans',                    'xlsx'],
    ['/api/report/workstations',             'xlsx'],

    // GDPR
    ['/api/report/activityList',             'xlsx'],
    ['/api/report/activityReport',           'docx'],

    // CVE
    ['/api/report/cve',                      'xlsx'],

];

/**
 * TESTS "forbidden" (403) pour TOUTES les routes,
 * avec utilisateur authentifié SANS permissions.
 */
it('forbids report endpoints without permission', function (string $endpoint) {
    $user = User::factory()->create();
    Passport::actingAs($user); // authentifié mais sans droits

    $this->get($endpoint)->assertForbidden();
})->with(
    array_map(fn($e) => [$e[0]], $FILE_REPORTS)
);

/**
 * TESTS "permitted" pour les endpoints FICHIERS
 */
it('returns a file download for report endpoints when permitted', function (string $endpoint, string $ext) {
    actAsAdminAllAllowed();

    $response = $this->get($endpoint);
    assertBinaryDownload($response, $ext);
})->with($FILE_REPORTS);

