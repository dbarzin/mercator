<?php
// app/Services/MonarcExportService.php
namespace App\Services;

use Illuminate\Support\Str;

class MonarcExportService
{
    public function __construct(private MospService $mosp) {}

    /**
     * @param array $referentialSlugs  ex: ["iso27002"]
     * @param array $assets            tableau d’assets Mercator (id,title,type,parent_id)
     */
    public function buildAnalysis(
        string $name,
        string $description,
        string $language,
        array $referentialSlugs,
        array $assets
    ): array {
        // 1) Charger référentiels + menaces/vulns/reco (depuis MOSP en mémoire)
        $referentials = [];
        foreach ($referentialSlugs as $slug) {
            $ref = $this->mosp->getReferential($slug);
            // On attend au moins uuid/title/version/slug
            $referentials[] = [
                'uuid'    => $ref['uuid']     ?? (string) Str::uuid(),
                'title'   => $ref['title']    ?? $slug,
                'version' => $ref['version']  ?? null,
                'slug'    => $slug,
                '_threats'=> $ref['threats']  ?? [], // interne pour scénarios
            ];
        }

        // 2) Mapper assets Mercator → objets MONARC (uuid v4, parent)
        $assetsJson = [];
        $assetUuid  = [];
        foreach ($assets as $a) {
            $uuid = (string) Str::uuid();
            $assetUuid[$a['id']] = $uuid;
            $assetsJson[] = [
                'uuid'   => $uuid,
                'name'   => $a['title'],
                'type'   => $a['type'], // 'primary' | 'supporting'
                'parent' => $a['parent_id'] ? ($assetUuid[$a['parent_id']] ?? null) : null,
            ];
        }

        // (facultatif) réparation parent si parent non sélectionné
        foreach ($assetsJson as &$aj) {
            if ($aj['parent'] === null && !empty($aj['_fallback_parent_uuid'])) {
                $aj['parent'] = $aj['_fallback_parent_uuid'];
            }
            unset($aj['_fallback_parent_uuid']);
        }

        // 3) Générer scénarios pour chaque asset 'supporting'
        $scenarios = [];
        foreach ($assets as $a) {
            if (($a['type'] ?? '') !== 'supporting') continue;
            $aUuid = $assetUuid[$a['id']];

            foreach ($referentials as $ref) {
                foreach ($ref['_threats'] as $t) {
                    $tUuid   = $t['uuid'] ?? (string) Str::uuid();
                    $vulns   = $t['vulnerabilities']   ?? [];
                    $recos   = $t['recommendations']   ?? [];
                    $recoIds = array_values(array_unique(array_map(fn($r)=>$r['uuid'] ?? null, $recos)));
                    $recoIds = array_values(array_filter($recoIds));

                    if (empty($vulns)) {
                        $scenarios[] = $this->scenario($aUuid, $tUuid, null, $recoIds);
                    } else {
                        foreach ($vulns as $v) {
                            $scenarios[] = $this->scenario($aUuid, $tUuid, $v['uuid'] ?? (string) Str::uuid(), $recoIds);
                        }
                    }
                }
            }
        }

        // 4) Nettoyer les référentiels (on ne garde que les méta dans le JSON final)
        $refsOut = array_map(fn($r)=>[
            'uuid'    => $r['uuid'],
            'title'   => $r['title'],
            'version' => $r['version'],
        ], $referentials);

        // 5) Payload final
        return [
            'analysis' => [
                'name'        => $name,
                'description' => $description,
                'language'    => $language,
                'referentials'=> $refsOut,
            ],
            'assets'    => $assetsJson,
            'scenarios' => $scenarios,
        ];
    }

    private function scenario(string $assetUuid, string $threatUuid, ?string $vulnUuid, array $recommendationUuids): array
    {
        // scores à 0: évaluations à faire dans MONARC
        return [
            'asset_uuid'  => $assetUuid,
            'threat_uuid' => $threatUuid,
            'vuln_uuid'   => $vulnUuid,
            'assessments' => [
                'likelihood' => 0,
                'impact'     => ['c'=>0,'i'=>0,'a'=>0],
            ],
            'measures'    => array_map(fn($u)=>[
                'recommendation_uuid' => $u,
                'status'              => 'planned',
            ], $recommendationUuids),
        ];
    }
}
