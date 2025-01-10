<?php

namespace App\Http\Controllers\Admin;

use App\Building;
use App\Entity;
use App\Http\Controllers\Controller;
use App\Information;
use App\MApplication;
use App\Process;
use App\Site;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class MonarcController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('activity_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $entities = Entity::select('id', 'name')->orderBy('name')->get();
        $processes = Process::select('id', 'name')->orderBy('name')->get();
        $informations = Information::select('id', 'name')->orderBy('name')->get();
        $applications = MApplication::select('id', 'name')->orderBy('name')->get();

        $sites = Site::select('id', 'name')->orderBy('name')->get();
        $buildings = Building::select('id', 'name')->orderBy('name')->get();

        return view(
            'monarc',
            compact(
                'entities',
                'processes',
                'informations',
                'applications',
                'sites',
                'buildings'
            )
        );
    }
}
