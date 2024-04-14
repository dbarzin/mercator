<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LogicalServer;
use App\MApplication;
use Carbon\Carbon;
use Gate;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PatchingController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('patching_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get Attributes
        $attributes_list = $this->getAttributes();

        // Get attributes
        if ($request->get('clear') !== null) {
            session()->forget('attributes_filter');
            $attributes_filter = [];
        } else {
            $attributes_filter = $request->get('attributes_filter');
            if ($attributes_filter === null) {
                $attributes_filter = session()->get('attributes_filter');
                if ($attributes_filter === null) {
                    $attributes_filter = [];
                }
            } else {
                session()->put('attributes_filter', $attributes_filter);
            }
        }

        // Select
        $servers = LogicalServer::select(
            DB::raw("'SRV' as type"),
            'id',
            'name',
            DB::raw('null as vendor'),
            DB::raw('null as product'),
            'operating_system as version',
            DB::raw('null as responsible'),
            'attributes',
            'update_date',
            'next_update'
        );
        $applications = MApplication::select(
            DB::raw(
                "'APP' as type"
            ),
            'id',
            'name',
            'vendor',
            'product',
            'version',
            'responsible',
            'attributes',
            'update_date',
            'next_update'
        );

        // Filter on attributes
        if ($attributes_filter !== null) {
            foreach ($attributes_filter as $a) {
                if (str_starts_with($a, '-')) {
                    $servers->where('attributes', 'not like', '%' . substr($a, 1) . '%');
                    $applications->where('attributes', 'not like', '%' . substr($a, 1) . '%');
                } else {
                    $servers->where('attributes', 'like', '%' . $a . '%');
                    $applications->where('attributes', 'like', '%' . $a . '%');
                }
            }
        } else {
            $attributes_filter = [];
        }
        // Union
        $patches = $servers->union($applications)->orderBy('name')->get();
        return view('admin.patching.index', compact('patches', 'attributes_list', 'attributes_filter'));
    }

    public function editServer(Request $request)
    {
        abort_if(Gate::denies('patching_make'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $server = LogicalServer::find($request->id);

        // Lists
        $attributes_list = $this->getAttributes();
        $operating_system_list = LogicalServer::select('operating_system')->where('operating_system', '<>', null)->distinct()->orderBy('operating_system')->pluck('operating_system');
        $environment_list = LogicalServer::select('environment')->where('environment', '<>', null)->distinct()->orderBy('environment')->pluck('environment');

        // Documents
        $documents = [];
        foreach ($server->documents as $doc) {
            array_push($documents, $doc->id);
        }
        session()->put('documents', $documents);

        return view(
            'admin.patching.server',
            compact(
                'server',
                'operating_system_list',
                'environment_list',
                'attributes_list'
            )
        );
    }

    public function editApplication(Request $request)
    {
        abort_if(Gate::denies('patching_make'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $application = MApplication::find($request->id);

        // Lists
        $attributes_list = $this->getAttributes();

        return view(
            'admin.patching.application',
            compact('application', 'attributes_list')
        );
    }

    public function updateServer(Request $request)
    {
        $logicalServer = LogicalServer::find($request->id);
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);
        $logicalServer->update($request->all());
        $logicalServer->documents()->sync(session()->get('documents'));
        session()->forget('documents');

        // Update frequency
        if ($request->get('global_periodicity') !== null) {
            $lservers = LogicalServer::where('attributes', '=', $logicalServer->attributes)->get();
            foreach ($lservers as $s) {
                $s->patching_frequency = $logicalServer->patching_frequency;
                if ($s->update_date !== null) {
                    $s->next_update = Carbon::createFromFormat(config('panel.date_format'), $s->update_date)->addMonth($logicalServer->patching_frequency)->format(config('panel.date_format'));
                }
                $s->save();
            }
        }

        return redirect()->route('admin.patching.index');
    }

    public function updateApplication(Request $request)
    {
        $application = MApplication::find($request->id);

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);
        $application->update($request->all());

        // $logicalServer->documents()->sync(session()->get('documents'));
        // session()->forget('documents');

        // Update frequency
        if ($request->get('global_periodicity') !== null) {
            $apps = MApplication::where('attributes', '=', $application->attributes)->get();
            foreach ($apps as $s) {
                $s->patching_frequency = $application->patching_frequency;
                if ($s->update_date !== null) {
                    $s->next_update = Carbon::createFromFormat(config('panel.date_format'), $s->update_date)->addMonth($application->patching_frequency)->format(config('panel.date_format'));
                }
                $s->save();
            }
        }

        return redirect()->route('admin.patching.index');
    }

    public function dashboard(Request $request)
    {
        // Get Attributes
        $attributes_list = $this->getAttributes();

        // Get attributes
        if ($request->get('clear') !== null) {
            session()->forget('attributes_filter');
            $attributes_filter = [];
        } else {
            $attributes_filter = $request->get('attributes_filter');
            if ($attributes_filter === null) {
                $attributes_filter = session()->get('attributes_filter');
                if ($attributes_filter === null) {
                    $attributes_filter = [];
                }
            } else {
                session()->put('attributes_filter', $attributes_filter);
            }
        }

        // Select
        $servers = LogicalServer::select(
            DB::raw("'SRV' as type"),
            'id',
            'name',
            DB::raw('null as vendor'),
            DB::raw('null as product'),
            'operating_system as version',
            DB::raw('null as responsible'),
            'attributes',
            'update_date',
            'next_update'
        );
        $applications = MApplication::select(
            DB::raw(
                "'APP' as type"
            ),
            'id',
            'name',
            'vendor',
            'product',
            'version',
            'responsible',
            'attributes',
            'update_date',
            'next_update'
        );

        // Filter on attributes
        if ($attributes_filter !== null) {
            foreach ($attributes_filter as $a) {
                if (str_starts_with($a, '-')) {
                    $servers->where('attributes', 'not like', '%' . substr($a, 1) . '%');
                    $applications->where('attributes', 'not like', '%' . substr($a, 1) . '%');
                } else {
                    $servers->where('attributes', 'like', '%' . $a . '%');
                    $applications->where('attributes', 'like', '%' . $a . '%');
                }
            }
        }

        // Union
        $patches = $servers->union($applications)->orderBy('name')->get();

        // select distinct attributes
        $res = [];
        foreach ($patches as $p) {
            foreach (explode(' ', $p->attributes) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);
        $active_attributes_list = array_unique($res);

        return view(
            'admin.patching.dashboard',
            compact(
                'patches',
                'attributes_list',
                'attributes_filter',
                'active_attributes_list'
            )
        );
    }
    private function getAttributes(): array
    {
        // Get Attributes
        $attributes_list = LogicalServer::select('attributes')
            ->where('attributes', '<>', null)
            ->distinct()
            ->pluck('attributes');
        $res = [];
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        $attributes_list = MApplication::select('attributes')
            ->where('attributes', '<>', null)
            ->distinct()
            ->pluck('attributes');
        foreach ($attributes_list as $i) {
            foreach (explode(' ', $i) as $j) {
                if (strlen(trim($j)) > 0) {
                    $res[] = trim($j);
                }
            }
        }
        sort($res);
        return array_unique($res);
    }
}
