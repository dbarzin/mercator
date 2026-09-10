<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNetworkSwitchRequest;
use App\Http\Requests\StoreNetworkSwitchRequest;
use App\Http\Requests\UpdateNetworkSwitchRequest;
use App\Models\Cartographer;
use App\Models\NetworkSwitch;
use App\Models\PhysicalSwitch;
use App\Models\Vlan;
use Gate;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class NetworkSwitchController extends Controller
{
    /**
     * Display a listing of network switches.
     *
     * Aborts with HTTP 403 if the current user is not authorized to access network switches.
     *
     * @return View View 'admin.networkSwitches.index' with `networkSwitches` ordered by `name`.
     */
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('network_switch_access') ? null : Cartographer::allowedIdsFor($user, NetworkSwitch::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $networkSwitches = NetworkSwitch::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (NetworkSwitch::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.networkSwitches.index', compact('networkSwitches'));
    }

    /**
     * Show the form for creating a new network switch.
     *
     * Provides name=>id lists of available physical switches and VLANs for form selectors.
     *
     * @return View The network switch creation view populated with `physicalSwitches` and `vlans`.
     */
    public function create()
    {
        abort_if(Gate::denies('network_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitches = PhysicalSwitch::query()->orderBy('name')->pluck('name', 'id');
        $vlans = Vlan::query()->orderBy('name')->pluck('name', 'id');
        $type_list = NetworkSwitch::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view('admin.networkSwitches.create',
            compact('physicalSwitches', 'vlans', 'type_list', 'attributes_list'));
    }

    /**
     * Create a new NetworkSwitch and persist its physical switch and VLAN associations.
     *
     * @param  StoreNetworkSwitchRequest  $request  Validated input including network switch attributes and optional `physicalSwitches` and `vlans` arrays to associate.
     * @return RedirectResponse Redirects to the network switches index route.
     */
    public function store(StoreNetworkSwitchRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $networkSwitch = NetworkSwitch::create($request->all());
        $networkSwitch->physicalSwitches()->sync($request->input('physicalSwitches', []));
        $networkSwitch->vlans()->sync($request->input('vlans', []));

        return redirect()->route('admin.network-switches.index');
    }

    /**
     * Show the form for editing the given network switch.
     *
     * @param  NetworkSwitch  $networkSwitch  The network switch to edit.
     * @return View The view displaying the network switch edit form populated with physical switch and VLAN options.
     *
     * @throws HttpException If the current user is denied the 'network_switch_edit' ability (HTTP 403).
     */
    public function edit(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('edit-object', $networkSwitch), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitches = PhysicalSwitch::query()->orderBy('name')->pluck('name', 'id');
        $vlans = Vlan::query()->orderBy('name')->pluck('name', 'id');
        $type_list = NetworkSwitch::query()->select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        return view(
            'admin.networkSwitches.edit',
            compact('networkSwitch', 'physicalSwitches', 'vlans', 'type_list', 'attributes_list')
        );
    }

    /**
     * Updates the given network switch and synchronizes its related physical switches and VLANs.
     *
     * The method applies all validated request data to the provided NetworkSwitch, syncs the
     * physicalSwitches and vlans relationships from the request (using an empty array if absent),
     * and redirects back to the network switches index.
     *
     * @param  UpdateNetworkSwitchRequest  $request  Validated input for updating the network switch.
     * @param  NetworkSwitch  $networkSwitch  The network switch instance to update.
     * @return RedirectResponse Redirect response to the network switches index route.
     */
    public function update(UpdateNetworkSwitchRequest $request, NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('edit-object', $networkSwitch), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $networkSwitch->update($request->all());
        $networkSwitch->physicalSwitches()->sync($request->input('physicalSwitches', []));
        $networkSwitch->vlans()->sync($request->input('vlans', []));

        return redirect()->route('admin.network-switches.index');
    }

    public function show(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('show-object', $networkSwitch), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.networkSwitches.show', compact('networkSwitch'));
    }

    public function destroy(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitch->delete();

        return redirect()->route('admin.network-switches.index');
    }

    /**
     * Delete multiple NetworkSwitch records identified by the request's `ids`.
     *
     * @param  MassDestroyNetworkSwitchRequest  $request  Request containing an `ids` array of NetworkSwitch IDs to delete.
     * @return \Illuminate\Http\Response HTTP 204 No Content response.
     */
    public function massDestroy(MassDestroyNetworkSwitchRequest $request)
    {
        NetworkSwitch::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = NetworkSwitch::query()
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
