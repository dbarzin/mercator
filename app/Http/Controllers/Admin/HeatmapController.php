<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyLanRequest;
use App\Http\Requests\StoreLanRequest;
use App\Http\Requests\UpdateLanRequest;
use App\Lan;
use Gate;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HeatmapController extends Controller
{
    public function index()
    {
        return view('admin.reports.heatmap');
    }

    public function index2()
    {
        return view('admin.reports.heatmap2');
    }

    public function values(Request $request)
    {
        $ipList = DB::table('logical_servers')
            ->select('address_ip')
            ->whereNotNull('address_ip')
            ->pluck('address_ip');

        $rangeFilter = $request->query('range'); // '10', '172', '192' ou null

        $heatmap = [];

        foreach ($ipList as $ips) {
            foreach (explode(',', $ips) as $ip) {
                if (!filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
                    continue;
                }

                $parts = explode('.', $ip);
                $octet1 = intval($parts[0]);
                $octet2 = intval($parts[1]);
                $octet3 = intval($parts[2]);

                $plage = null;

                if ($octet1 === 10) {
                    $plage = '10';
                } elseif ($octet1 === 172 && $octet2 >= 16 && $octet2 <= 31) {
                    $plage = '172';
                } elseif ($octet1 === 192 && $octet2 === 168) {
                    $plage = '192';
                }

                if ($plage === null) continue;
                if ($rangeFilter && $plage !== $rangeFilter) continue;

                $y = $octet2;
                $x = $octet3;
                $key = "$plage-$x-$y";

                if (!isset($heatmap[$key])) {
                    $heatmap[$key] = [
                        'x' => $x,
                        'y' => $y,
                        'v' => 0,
                        'range' => $plage
                    ];
                }

                $heatmap[$key]['v'] += 1;
            }
        }

        return array_values($heatmap);
    }

}
