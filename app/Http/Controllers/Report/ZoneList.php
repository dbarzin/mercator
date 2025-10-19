<?php

declare(strict_types=1);

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Subnetwork;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ZoneList extends Controller
{
    public function generate()
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $subnetworks = Subnetwork::All()->sortBy('zone, address');

        return view('admin/reports/zones', compact('subnetworks'));
    }
}
