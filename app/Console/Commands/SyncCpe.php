<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class SyncCpe extends Command
{
    protected $signature = 'cpe:sync
                            {--per-page=2000 : Number of items per page (max NVD 10000)}
                            {--max=0 : Maximum number of items to process (0 = unlimited)}
                            {--full : Ignore lastMod* window and fetch the full dictionary}
                            {--since= : ISO8601 UTC, ex. 2025-09-20T00:00:00Z (override cache)}
                            {--now : Run immediately, skip random delay}';

    protected $description = 'Synchronize the database with the CPE dictionary (vendors, products, versions) from NVD.';

    /**
     * Parse a CPE 2.3 URI, handling escaped characters (\:).
     * Returns [part, vendor, product, version] or null if invalid.
     */
    protected function parseCpe23(string $uri): ?array
    {
        if (! Str::startsWith($uri, 'cpe:2.3:')) {
            return null;
        }

        // Split ":" but respect "\:"
        $segments = [];
        $current = '';
        $len = strlen($uri);
        for ($i = 0; $i < $len; $i++) {
            $ch = $uri[$i];
            if ($ch === '\\' && $i + 1 < $len) {
                $current .= $uri[$i + 1];
                $i++;

                continue;
            }
            if ($ch === ':') {
                $segments[] = $current;
                $current = '';

                continue;
            }
            $current .= $ch;
        }
        $segments[] = $current;

        if (count($segments) < 6) {
            return null;
        }

        [$cpe, $ver, $part, $vendor, $product, $version] = array_pad($segments, 6, null);
        if ($cpe !== 'cpe' || $ver !== '2.3') {
            return null;
        }

        $normalize = function ($s) {
            if ($s === null) {
                return null;
            }
            if ($s === '*' || $s === '-') {
                return null;
            } // ANY/NA -> null

            return $s;
        };

        return [
            'part' => $normalize($part) ?: 'a', // default 'a' (applications)
            'vendor' => $normalize($vendor) ?: 'unknown',
            'product' => $normalize($product) ?: 'unknown',
            'version' => $normalize($version),
        ];
    }

    public function handle(): int
    {
        // Optional random startup delay (0–15 minutes) unless --now is provided
        if (! $this->option('now')) {
            $delay = rand(0, 900); // seconds
            $this->info("Random startup delay: sleeping for {$delay} seconds...");
            sleep($delay);
        }

        $apiUrl = config('services.cpe.api_url', env('CPE_API_URL', 'https://services.nvd.nist.gov/rest/json/cpes/2.0'));
        $apiKey = config('services.cpe.api_key', env('NVD_API_KEY'));
        $perPage = min(max((int) $this->option('per-page'), 1), 10000);
        $maxItems = (int) $this->option('max');
        $isFull = (bool) $this->option('full');
        $sinceOpt = $this->option('since');

        $this->info("Source CPE API: {$apiUrl}");

        // Determine sync time window (UTC) for incremental mode
        $nowUtc = now('UTC');
        $start = null;
        $end = null;

        if (! $isFull) {
            if ($sinceOpt) {
                try {
                    $start = Carbon::parse($sinceOpt)->utc();
                } catch (\Throwable $e) {
                    $this->error("Invalid --since option: {$sinceOpt}");

                    return self::FAILURE;
                }
            } else {
                $lastRun = cache()->get('cpe_sync.last_run');
                $start = $lastRun ? Carbon::parse($lastRun)->utc() : $nowUtc->clone()->subDay(); // default 24h back
            }
            $end = $nowUtc;
            $this->line("Incremental window: {$start->toIso8601String()} -> {$end->toIso8601String()}");
        } else {
            $this->warn('FULL resync mode: ignoring lastMod*, fetching the entire dictionary.');
        }

        // HTTP client with retry & timeout
        $client = Http::timeout(120)
            ->retry(5, 1000, fn () => true) // retry on transient errors
            ->withHeaders([
                'User-Agent' => 'Mercator/1.0 (+https://sourcentis.com)',
            ]);

        if ($apiKey) {
            $client = $client->withHeaders(['apiKey' => $apiKey]);
        }

        $startIndex = 0;
        $processed = 0;
        $createdVendors = 0;
        $createdProducts = 0;
        $createdVersions = 0;

        // Pagination loop
        while (true) {
            $params = [
                'resultsPerPage' => $perPage,
                'startIndex' => $startIndex,
            ];

            if (! $isFull) {
                $params['lastModStartDate'] = $start->format('Y-m-d\TH:i:s.v\Z');
                $params['lastModEndDate'] = $end->format('Y-m-d\TH:i:s.v\Z');
            }

            // ->throw() raises a RequestException on 4xx/5xx
            $response = $client->get($apiUrl, $params)->throw();
            $json = $response->json();

            $total = (int) ($json['totalResults'] ?? 0);
            $items = $json['products'] ?? [];

            if ($startIndex === 0) {
                $this->info("totalResults: {$total}");
            }

            if (empty($items)) {
                if ($startIndex === 0) {
                    $this->warn('No items returned for these parameters.');
                }
                break;
            }

            // Wrap in transaction for batch insert performance
            DB::beginTransaction();
            try {
                foreach ($items as $item) {
                    $uri = $item['cpe']['cpeName'] ?? $item['cpeName'] ?? $item['cpe23Uri'] ?? null;
                    if (! $uri) {
                        continue;
                    }

                    $parsed = $this->parseCpe23($uri);
                    if (! $parsed) {
                        continue;
                    }

                    $part = $parsed['part'];
                    $vendorN = $parsed['vendor'];
                    $productN = $parsed['product'];
                    $versionN = $parsed['version'];

                    // 1) Vendor
                    $vendorId = DB::table('cpe_vendors')
                        ->where('part', $part)
                        ->where('name', $vendorN)
                        ->value('id');

                    if (! $vendorId) {
                        $vendorId = DB::table('cpe_vendors')->insertGetId([
                            'part' => $part,
                            'name' => $vendorN,
                        ]);
                        $createdVendors++;
                    }

                    // 2) Product
                    $productId = DB::table('cpe_products')
                        ->where('cpe_vendor_id', $vendorId)
                        ->where('name', $productN)
                        ->value('id');

                    if (! $productId) {
                        $productId = DB::table('cpe_products')->insertGetId([
                            'cpe_vendor_id' => $vendorId,
                            'name' => $productN,
                        ]);
                        $createdProducts++;
                    }

                    // 3) Version
                    if ($versionN) {
                        $versionId = DB::table('cpe_versions')
                            ->where('cpe_product_id', $productId)
                            ->where('name', $versionN)
                            ->value('id');

                        if (! $versionId) {
                            DB::table('cpe_versions')->insert([
                                'cpe_product_id' => $productId,
                                'name' => $versionN,
                            ]);
                            $createdVersions++;
                        }
                    }

                    $processed++;
                    if ($maxItems > 0 && $processed >= $maxItems) {
                        break 2; // exit both loops
                    }
                }

                DB::commit();
            } catch (\Throwable $e) {
                DB::rollBack();
                $this->error('Insert error: '.$e->getMessage());

                return self::FAILURE;
            }

            $startIndex += count($items);
            $this->info("Progress: {$startIndex}/{$total}");

            if ($startIndex >= $total) {
                break;
            }
        }

        // Store last successful run timestamp for incremental sync
        if (! $isFull) {
            cache()->forever('cpe_sync.last_run', $end->toIso8601String());
        }

        $this->info("Done. Processed: {$processed}. New => vendors: {$createdVendors}, products: {$createdProducts}, versions: {$createdVersions}");

        return self::SUCCESS;
    }
}
