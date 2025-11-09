<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyNetworkSwitchRequest;
use App\Http\Requests\StoreNetworkSwitchRequest;
use App\Http\Requests\UpdateNetworkSwitchRequest;
use App\Models\NetworkSwitch;
use App\Models\PhysicalSwitch;
use App\Models\Vlan;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class NetworkSwitchController extends Controller
{
    /**
     * Display a listing of network switches.
     *
     * Aborts with HTTP 403 if the current user is not authorized to access network switches.
     *
     * @return \Illuminate\View\View View 'admin.networkSwitches.index' with `networkSwitches` ordered by `name`.
     */
    public function index()
    {
        abort_if(Gate::denies('network_switch_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networkSwitches = NetworkSwitch::all()->sortBy('name');

        return view('admin.networkSwitches.index', compact('networkSwitches'));
    }

    /**
     * Show the form for creating a new network switch.
     *
     * Provides name=>id lists of available physical switches and VLANs for form selectors.
     *
     * @return \Illuminate\View\View The network switch creation view populated with `physicalSwitches` and `vlans`.
     */
    public function create()
    {
        abort_if(Gate::denies('network_switch_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitches = PhysicalSwitch::query()->orderBy('name')->pluck('name', 'id');
        $vlans = Vlan::query()->orderBy('name')->pluck('name', 'id');

        return view('admin.networkSwitches.create',
            compact('physicalSwitches', 'vlans'));
    }

    /**
     * Create a new NetworkSwitch and persist its physical switch and VLAN associations.
     *
     * @param \App\Http\Requests\StoreNetworkSwitchRequest $request Validated input including network switch attributes and optional `physicalSwitches` and `vlans` arrays to associate.
     * @return \Illuminate\Http\RedirectResponse Redirects to the network switches index route.
     */
    public function store(StoreNetworkSwitchRequest $request)
    {
        $networkSwitch = NetworkSwitch::create($request->all());
        $networkSwitch->physicalSwitches()->sync($request->input('physicalSwitches', []));
        $networkSwitch->vlans()->sync($request->input('vlans', []));

        return redirect()->route('admin.network-switches.index');
    }

    /**
     * Show the form for editing the given network switch.
     *
     * @param NetworkSwitch $networkSwitch The network switch to edit.
     * @return \Illuminate\View\View The view displaying the network switch edit form populated with physical switch and VLAN options.
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException If the current user is denied the 'network_switch_edit' ability (HTTP 403).
     */
    public function edit(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $physicalSwitches = PhysicalSwitch::query()->orderBy('name')->pluck('name', 'id');
        $vlans = Vlan::query()->orderBy('name')->pluck('name', 'id');

        return view(
            'admin.networkSwitches.edit',
            compact('networkSwitch', 'physicalSwitches','vlans')
        );
    }

    /**
     * Updates the given network switch and synchronizes its related physical switches and VLANs.
     *
     * The method applies all validated request data to the provided NetworkSwitch, syncs the
     * physicalSwitches and vlans relationships from the request (using an empty array if absent),
     * and redirects back to the network switches index.
     *
     * @param \App\Http\Requests\UpdateNetworkSwitchRequest $request Validated input for updating the network switch.
     * @param \App\Models\NetworkSwitch $networkSwitch The network switch instance to update.
     * @return \Illuminate\Http\RedirectResponse Redirect response to the network switches index route.
     */
    public function update(UpdateNetworkSwitchRequest $request, NetworkSwitch $networkSwitch)
    {
        $networkSwitch->update($request->all());
        $networkSwitch->physicalSwitches()->sync($request->input('physicalSwitches', []));
        $networkSwitch->vlans()->sync($request->input('vlans', []));

        return redirect()->route('admin.network-switches.index');
    }

    public function show(NetworkSwitch $networkSwitch)
    {
        abort_if(Gate::denies('network_switch_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
     * @param \App\Http\Requests\MassDestroyNetworkSwitchRequest $request Request containing an `ids` array of NetworkSwitch IDs to delete.
     * @return \Illuminate\Http\Response HTTP 204 No Content response.
     */
    public function massDestroy(MassDestroyNetworkSwitchRequest $request)
    {
        NetworkSwitch::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}