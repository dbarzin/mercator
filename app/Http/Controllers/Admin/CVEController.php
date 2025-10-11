<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class CVEController extends Controller
{
    public function search(string $cpe)
    {
        $provider = config('mercator-config.cve.provider');
        if (empty($provider)) {
            return back()->withErrors('CVE provider is not set.');
        }

        try {
            $cves = $this->getCVEs($provider, $cpe);
        } catch (\Throwable $e) {
            return back()->withErrors('CVE search failed: '.$e->getMessage());
        }

        return view('admin.cve.show', compact('cpe', 'cves'));
    }

    public function list()
    {
        Log::debug('CVEReport - Start');

        $provider = config('mercator-config.cve.provider');
        if (empty($provider)) {
            return back()->withErrors('CVE provider is not set.');
        }

        $applications = DB::table('m_applications')
            ->select('name', 'vendor', 'product', 'version')
            ->orderBy('name')
            ->get();

        $header = [
            trans('cruds.application.fields.name'),
            'CPE Vendor',
            'CPE Name',
            'CPE Version',
            'CVE',
            'CVE Affected Version',
            'CVE Summary',
            'CVE References',
            'CVE Impact',
            'CVE Score',
            'CVE Published',
        ];

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->fromArray([$header], null, 'A1');

        // Header style
        $sheet->getStyle('1')->getFont()->setBold(true);

        // Column sizes
        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
        $sheet->getColumnDimension('C')->setAutoSize(true);
        $sheet->getColumnDimension('D')->setAutoSize(true);
        $sheet->getColumnDimension('E')->setAutoSize(true);
        $sheet->getColumnDimension('F')->setAutoSize(true);
        $sheet->getColumnDimension('G')->setWidth(120);
        $sheet->getColumnDimension('H')->setAutoSize(true);
        $sheet->getColumnDimension('I')->setAutoSize(true);
        $sheet->getColumnDimension('J')->setAutoSize(true);

        // Wrap text for Summary & References
        $sheet->getStyle('G')->getAlignment()->setWrapText(true);
        $sheet->getStyle('G')->getAlignment()->setVertical(Alignment::VERTICAL_TOP);

        $row = 2;

        foreach ($applications as $app) {
            Log::debug('CVEReport - app->name '.$app->name);

            // Cas incomplet : on consigne et on passe
            if (empty($app->vendor) || empty($app->product)) {
                $sheet->setCellValue("A{$row}", $app->name);
                $sheet->setCellValue("B{$row}", $app->vendor);
                $sheet->setCellValue("C{$row}", $app->product);
                $sheet->setCellValue("D{$row}", $app->version);
                $sheet->setCellValue("E{$row}", 'unknown');
                $row++;

                continue;
            }

            // Construit le CPE (version éventuellement vide)
            $version = $app->version ?? '';
            $cpe = "cpe:2.3:a:{$app->vendor}:{$app->product}:{$version}";

            try {
                // Utilise la fonction fiable écrite précédemment
                $cves = $this->getCVEs($provider, $cpe);
            } catch (\Throwable $e) {
                Log::warning('CVEReport - fetch failed', [
                    'app' => $app->name,
                    'cpe' => $cpe,
                    'error' => $e->getMessage(),
                ]);
                // Écrit une ligne d’erreur mais on continue
                $sheet->setCellValue("A{$row}", $app->name);
                $sheet->setCellValue("B{$row}", $app->vendor);
                $sheet->setCellValue("C{$row}", $app->product);
                $sheet->setCellValue("D{$row}", $version);
                $sheet->setCellValue("E{$row}", 'error');
                $sheet->setCellValue("F{$row}", 'CVE search failed: '.$e->getMessage());
                $row++;
                // petite pause
                usleep(20000); // 20 ms

                continue;
            }

            // Pas de CVE => on documente quand même
            if (empty($cves)) {
                $sheet->setCellValue("A{$row}", $app->name);
                $sheet->setCellValue("B{$row}", $app->vendor);
                $sheet->setCellValue("C{$row}", $app->product);
                $sheet->setCellValue("D{$row}", $version);
                $sheet->setCellValue("E{$row}", 'none');
                $row++;
                usleep(20000);

                continue;
            }

            // Écrit chaque CVE (structure venant de getCVEs)
            foreach ($cves as $cve) {
                $sheet->setCellValue("A{$row}", $app->name);
                $sheet->setCellValue("B{$row}", $app->vendor);
                $sheet->setCellValue("C{$row}", $app->product);
                $sheet->setCellValue("D{$row}", $version);

                $sheet->setCellValue("E{$row}", $cve->cveId ?? '');
                $sheet->setCellValue("F{$row}", $cve->version ?? '');
                $sheet->setCellValue("G{$row}", $cve->description ?? ($cve->title ?? ''));

                // Références : on concatène nom + URL si dispo
                $ref = trim(
                    ($cve->name ?? '').
                    (isset($cve->url) && $cve->url ? " \n".$cve->url : '')
                );
                $sheet->setCellValue("H{$row}", $ref);

                $sheet->setCellValue("I{$row}", $cve->baseSeverity ?? '');
                $sheet->setCellValue("J{$row}", $cve->baseScore ?? '');
                $sheet->setCellValue("K{$row}", $cve->datePublished ?? '');

                $row++;
            }

            // Politesse pour l’API (réellement quelques millisecondes)
            usleep(20000); // 20 ms
        }

        $writer = new Xlsx($spreadsheet);
        $path = storage_path('app/cve-'.Carbon::today()->format('Ymd').'.xlsx');
        $writer->save($path);

        Log::debug('CVEReport - Done.');

        return response()->download($path)->deleteFileAfterSend(true);
    }

    /**
     * @return array<int,object> // liste d'objets CVE normalisés
     *
     * @throws \RuntimeException // en cas d'erreur
     */
    protected function getCVEs(string $provider, string $cpe): array
    {
        // Construit l’URL proprement et encode le CPE en segment d’URL
        $base = rtrim($provider, '/');
        $url = $base.'/api/vulnerability/cpesearch/'.rawurlencode($cpe);

        $appVersion = trim(file_get_contents(base_path('version.txt')));

        // Appel HTTP avec timeouts et gestion d’erreurs
        $resp = Http::timeout(10)
            ->acceptJson()
            ->withHeaders(['User-Agent' => "Mercator/{$appVersion}"])
            ->get($url);

        if ($resp->failed()) {
            // inclut 4xx/5xx; on expose un message court
            throw new \RuntimeException("HTTP {$resp->status()} from provider");
        }

        // JSON valide ?
        $json = $resp->json();
        if (! is_array($json) && ! is_object($json)) {
            throw new \RuntimeException('Invalid JSON payload');
        }

        // Uniformise l’accès: tableau associatif
        $data = is_array($json) ? $json : (array) $json;

        // La clé peut s’appeler cvelistv5 (souvent) ou cvelist (fallback)
        $list = $data['cvelistv5'] ?? $data['cvelist'] ?? null;
        if (! is_array($list)) {
            // Pas d’erreur “bloquante” → retourne liste vide
            return [];
        }

        // get version
        $cpeVersion = $this->extractCpeVersion($cpe);

        // Map sécurisé des champs (beaucoup de champs peuvent manquer)
        $out = [];
        foreach ($list as $cve) {
            // On passe par Arr::get pour éviter les notices
            $cveArr = (array) $cve;

            $cveId = Arr::get($cveArr, 'cveMetadata.cveId', '');
            $datePublished = substr((string) Arr::get($cveArr, 'cveMetadata.datePublished', ''), 0, 10);
            $dateUpdated = substr((string) Arr::get($cveArr, 'cveMetadata.dateUpdated', ''), 0, 10);

            // CNA container
            $title = Arr::get($cveArr, 'containers.cna.title', '');
            $description = Arr::get($cveArr, 'containers.cna.descriptions.0.value', '');
            $refUrl = Arr::get($cveArr, 'containers.cna.references.0.url', '');
            $refName = Arr::get($cveArr, 'containers.cna.references.0.name', '');
            $cveVersion = Arr::get($cveArr, 'containers.cna.affected.0.versions.0.version', '');

            // Apply filter
            // if (($cpeVersion!==null) && !$this->isVersionGreater($cveVersion, $cpeVersion))
            //    continue;

            // Scores (essaie CNA V3.0, V3.1, puis ADP)
            $baseScore = Arr::get($cveArr, 'containers.cna.metrics.0.cvssV3_0.baseScore')
                ?? Arr::get($cveArr, 'containers.cna.metrics.0.cvssV3_1.baseScore')
                ?? Arr::get($cveArr, 'containers.adp.0.metrics.0.cvssV3_1.baseScore')
                ?? '';

            $baseSeverity = Arr::get($cveArr, 'containers.cna.metrics.0.cvssV3_0.baseSeverity')
                ?? Arr::get($cveArr, 'containers.cna.metrics.0.cvssV3_1.baseSeverity')
                ?? Arr::get($cveArr, 'containers.adp.0.metrics.0.cvssV3_1.baseSeverity')
                ?? '';

            $out[] = (object) [
                'cveId' => $cveId,
                'title' => $title,
                'description' => $description,
                'url' => $refUrl,
                'version' => $cveVersion,
                'name' => $refName,
                'datePublished' => $datePublished,
                'dateUpdated' => $dateUpdated,
                'baseScore' => $baseScore,
                'baseSeverity' => $baseSeverity,
            ];
        }

        // dd($out);
        return $out;
    }

    protected function extractCpeVersion(string $cpe): ?string
    {
        $parts = explode(':', $cpe);

        if (count($parts) >= 5) {
            $version = $parts[5];

            return $version !== '*' ? $version : null;
        }

        return null;
    }

    protected function isVersionGreater(string $v1, string $v2): bool
    {
        $parts1 = array_map('intval', explode('.', $v1));
        $parts2 = array_map('intval', explode('.', $v2));

        $length = max(count($parts1), count($parts2));
        $parts1 = array_pad($parts1, $length, 0);
        $parts2 = array_pad($parts2, $length, 0);

        for ($i = 0; $i < $length; $i++) {
            if ($parts1[$i] > $parts2[$i]) {
                return true;
            }
            if ($parts1[$i] < $parts2[$i]) {
                return false;
            }
        }

        return false; // égales
    }
}
