<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyWifiTerminalRequest;
use App\Http\Requests\StoreWifiTerminalRequest;
use App\Http\Requests\UpdateWifiTerminalRequest;
use App\Models\Building;
use App\Models\Cartographer;
use App\Models\Site;
use App\Models\WifiTerminal;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WifiTerminalController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('wifi_terminal_access') ? null : Cartographer::allowedIdsFor($user, WifiTerminal::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $wifiTerminals = WifiTerminal::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (WifiTerminal::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.wifiTerminals.index', compact('wifiTerminals'));
    }

    public function create()
    {
        abort_if(Gate::denies('wifi_terminal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');

        $type_list = WifiTerminal::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.wifiTerminals.create',
            compact('type_list', 'sites', 'buildings', 'buildingSiteMap', 'attributes_list')
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('wifi_terminal_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');

        $type_list = WifiTerminal::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Get WifiTerminal
        $wifiTerminal = WifiTerminal::find($request['id']);

        // WifiTerminal not found
        abort_if($wifiTerminal === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $data = $wifiTerminal->only($wifiTerminal->getFillable());
        if (isset($data['attributes']) && is_string($data['attributes'])) {
            $data['attributes'] = array_filter(explode(' ', $data['attributes']));
        }

        $request->merge($data);
        $request->flash();

        return view(
            'admin.wifiTerminals.create',
            compact('type_list', 'sites', 'buildings', 'buildingSiteMap', 'attributes_list')
        );
    }

    public function store(StoreWifiTerminalRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        WifiTerminal::create($request->all());

        return redirect()->route('admin.wifi-terminals.index');
    }

    public function edit(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('edit-object', $wifiTerminal), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');
        $buildingSiteMap = Building::pluck('site_id', 'id');

        $type_list = WifiTerminal::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        $wifiTerminal->load('site', 'building');

        return view(
            'admin.wifiTerminals.edit',
            compact('sites', 'buildings', 'buildingSiteMap', 'wifiTerminal', 'type_list', 'attributes_list')
        );
    }

    public function update(UpdateWifiTerminalRequest $request, WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('edit-object', $wifiTerminal), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if (! $request->has('type')) {
            $request->merge(['type' => '']);
        }

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $wifiTerminal->update($request->all());

        return redirect()->route('admin.wifi-terminals.index');
    }

    public function show(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('show-object', $wifiTerminal), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->load('site', 'building');

        return view('admin.wifiTerminals.show', compact('wifiTerminal'));
    }

    public function destroy(WifiTerminal $wifiTerminal)
    {
        abort_if(Gate::denies('wifi_terminal_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $wifiTerminal->delete();

        return redirect()->route('admin.wifi-terminals.index');
    }

    public function massDestroy(MassDestroyWifiTerminalRequest $request)
    {
        WifiTerminal::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = WifiTerminal::query()
            ->select('attributes')
            ->where('attributes', '<>', null)
            ->pluck('attributes');
        $res = [];
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
