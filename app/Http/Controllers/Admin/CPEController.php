<?php

namespace App\Http\Controllers\Admin;

use App\CPEVendor;
use App\CPEVersion;
use App\CPEProduct;

use App\Http\Controllers\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CPEController extends Controller
{
    public function vendors(Request $request) {
        $part = $request->query('part');

        $query = CPEVendor::limit(20);
        $query->where('part', '=', $part);

        $search = $request->query('search');
        if ($search) {
            $query->where('name', 'LIKE', $search . '%');
            }

        $vendors = $query->get();
        return response()->json($vendors);
        }

    public function products(Request $request) {
        $part = $request->query('part');
        $vendor = $request->query('vendor');

        $query = CPEProduct::limit(20)
            ->select('cpe_products.name')
            ->join('cpe_vendors', 'cpe_vendors.id', '=', 'cpe_vendor_id')
            ->where('cpe_vendors.part', '=', $part)
            ->where('cpe_vendors.name', '=', $vendor);

        $search = $request->query('search');
        if ($search) {
            $query->where('cpe_products.name', 'LIKE', $search . '%');
            }

        $products = $query->get();
        return response()->json($products);
        }

    public function versions(Request $request) {
        $part = $request->query('part');
        $vendor = $request->query('vendor');
        $product = $request->query('product');

        $query = CPEVersion::limit(20)
            ->select('cpe_versions.name')
            ->join('cpe_products', 'cpe_products.id', '=', 'cpe_product_id')
            ->join('cpe_vendors', 'cpe_vendors.id', '=', 'cpe_vendor_id')
            ->where('cpe_products.name', '=', $product)
            ->where('cpe_vendors.part', '=', $part)
            ->where('cpe_vendors.name', '=', $vendor);

        $search = $request->query('search');
        if ($search) {
            $query->where('cpe_versions.name', 'LIKE', $search . '%');
        }
        $versions = $query->get();
        return response()->json($versions);
       }

    }