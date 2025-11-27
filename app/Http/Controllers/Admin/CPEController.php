<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Mercator\Core\Models\CPEProduct;
use Mercator\Core\Models\CPEVendor;
use Mercator\Core\Models\CPEVersion;
use Illuminate\Http\Request;

class CPEController extends Controller
{
    public function vendors(Request $request)
    {
        $query = CPEVendor::limit(100);

        $part = $request['part'];
        if ($part !== null) {
            $query->where('part', '=', $part);
        }

        $search = $request['search'];
        if ($search) {
            $query->where('name', 'LIKE', $search.'%');
        }

        $vendors = $query->get();

        return response()->json($vendors);
    }

    public function products(Request $request)
    {
        $part = $request['part'];
        $vendor = $request['vendor'];

        $query = CPEProduct::limit(100)
            ->select('cpe_products.name')
            ->join('cpe_vendors', 'cpe_vendors.id', '=', 'cpe_vendor_id')
            ->where('cpe_vendors.part', '=', $part)
            ->where('cpe_vendors.name', '=', $vendor);

        $search = $request['search'];
        if ($search) {
            $query->where('cpe_products.name', 'LIKE', $search.'%');
        }

        $products = $query->get();

        return response()->json($products);
    }

    public function versions(Request $request)
    {
        $part = $request['part'];
        $vendor = $request['vendor'];
        $product = $request['product'];

        $query = CPEVersion::select('cpe_versions.name')
            ->join('cpe_products', 'cpe_products.id', '=', 'cpe_product_id')
            ->join('cpe_vendors', 'cpe_vendors.id', '=', 'cpe_vendor_id')
            ->where('cpe_products.name', '=', $product)
            ->where('cpe_vendors.part', '=', $part)
            ->where('cpe_vendors.name', '=', $vendor);

        $search = $request['search'];
        if ($search) {
            $query->where('cpe_versions.name', 'LIKE', $search.'%');
        }
        $versions = $query->get();

        return response()->json($versions);
    }

    public function guess(Request $request)
    {
        $search = $request['search'];

        $query = CPEVendor::select('cpe_vendors.name as vendor_name', 'cpe_products.name as product_name')
            ->join('cpe_products', 'cpe_vendor_id', '=', 'cpe_vendors.id')
            ->where('cpe_products.name', 'like', '%'.$search.'%')
            ->limit(100);

        $result = $query->get();

        return response()->json($result);
    }

    /* TODO : please test me
    public function guess(Request $request)
    {
        \Log::debug($request->search);

        // 1) Entrée & nettoyage
        $raw = (string) $request->input('search', '');
        if ($raw === '') {
            return response()->json(['error' => 'Missing "search" parameter'], 422);
        }

        // Découpe simple en mots (on retire la ponctuation superflue)
        $keywords = collect(preg_split('/\s+/', Str::of($raw)->lower()->toString()))
            ->filter(fn ($w) => $w !== '')
            ->values()
            ->all();

        // 2) Configuration
        $baseUrl = rtrim(config('services.cpe_guesser.url', env('CPE_GUESSER_URL', 'https://cpe-guesser.cve-search.org')), '/');
        $endpoint = config('services.cpe_guesser.endpoint', env('CPE_GUESSER_ENDPOINT', 'search')); // "search" ou "unique"
        $timeout = (int) config('services.cpe_guesser.timeout', env('CPE_GUESSER_TIMEOUT', 6));    // secondes

        // 3) Appel API
        try {
            $response = Http::timeout($timeout)
                ->retry(5, 500, fn () => true) // retry on transient errors
                ->acceptJson()
                ->withHeaders([
                    'User-Agent' => 'mercator',
                ])
                ->asJson()
                ->post("{$baseUrl}/{$endpoint}", [
                    'query' => $keywords,
                ]);
            \Log::debug($response->json());

            if (! $response->successful()) {
                return response()->json([
                    'error' => 'CPE Guesser API error',
                    'status' => $response->status(),
                    'body' => $response->json() ?? $response->body(),
                ], 502);
            }

            $payload = $response->json();

            // 4) Normalisation de la sortie
            // - /search renvoie: [[<id>, "<cpe23>"], ...]
            // - /unique renvoie: "<cpe23>" (string) ou null
            if ($endpoint === 'unique') {
                $result = $payload
                    ? [['cpe_id' => null, 'cpe23' => (string) $payload]]
                    : [];
            } else {
                $result = collect($payload)
                    ->map(function ($row) {
                        // chaque $row est [id, "cpe:2.3:..."]
                        return [
                            'cpe_id' => $row[0] ?? null,
                            'cpe23' => $row[1] ?? null,
                        ];
                    })
                    ->values()
                    ->all();
            }

            $result = response()->json([
                'query' => $keywords,
                'source' => 'cpe-guesser',
                'count' => count($result),
                'items' => $result,
            ]);

            \Log::debug($result['items']);

            return $result;

        } catch (\Throwable $e) {
            return response()->json([
                'error' => 'Failed to reach CPE Guesser',
                'message' => $e->getMessage(),
            ], 502);
        }
    }
    */
}
