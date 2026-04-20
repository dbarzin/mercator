<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Log;
use App\Models\CPEProduct;
use App\Models\CPEVendor;
use App\Models\CPEVersion;

class CPEController extends Controller
{
    public function vendors(Request $request)
    {
        $query = CPEVendor::query()->limit(100);

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

        $cpeGuesserUrl = config('mercator.parameters.cpe.guesser');

        if ($cpeGuesserUrl) {
            // Découpage de la recherche en mots-clés pour l'API cpe-guesser
            $keywords = array_values(array_filter(explode(' ', trim($search))));

            if (empty($keywords)) {
                return response()->json([]);
            }

            try {
                $response = Http::timeout(5)
                    ->withUserAgent('Mercator/' . trim(file_get_contents(base_path('version.txt'))))
                    ->post(rtrim($cpeGuesserUrl, '/') . '/search', [
                        'query' => $keywords,
                    ]);

                if ($response->failed()) {
                    Log::warning('cpe-guesser request failed', [
                        'status' => $response->status(),
                        'url'    => $cpeGuesserUrl,
                    ]);
                    return response()->json([]);
                }

                // L'API retourne un tableau de CPE strings, ex:
                // ["cpe:2.3:a:microsoft:outlook:*:*:*:*:*:*:*:*", ...]
                // On les transforme en {vendor_name, product_name} pour garder
                // la même structure qu'avant côté client.

                $cpes = $response->json();

                $result = collect($cpes)
                    ->map(function (array $cpe) {
                        // Format : [score, "cpe:2.3:a:vendor:product:..."]
                        $parts = explode(':', $cpe[1]);
                        return [
                            'vendor_name'  => $parts[3] ?? '',
                            'product_name' => $parts[4] ?? '',
                        ];
                    })
                    ->unique(fn ($item) => $item['vendor_name'] . ':' . $item['product_name'])
                    ->values();


                return response()->json($result);

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::warning('cpe-guesser unreachable', ['error' => $e->getMessage()]);
                // Return fallback
                // return response()->json([]);
            }
        }

        // Fallback : recherche locale dans la base de données
        $result = CPEVendor::query()
            ->select('cpe_vendors.name as vendor_name', 'cpe_products.name as product_name')
            ->join('cpe_products', 'cpe_vendor_id', '=', 'cpe_vendors.id')
            ->where('cpe_products.name', 'like', '%' . $search . '%')
            ->limit(100)
            ->get();

        return response()->json($result);
    }

    /*
    public function guess(Request $request)
    {
        $search = $request['search'];

        $query = CPEVendor::query()->select('cpe_vendors.name as vendor_name', 'cpe_products.name as product_name')
            ->join('cpe_products', 'cpe_vendor_id', '=', 'cpe_vendors.id')
            ->where('cpe_products.name', 'like', '%'.$search.'%')
            ->limit(100);

        $result = $query->get();

        return response()->json($result);
    }
    */

}
