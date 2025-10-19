<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyPeripheralRequest;
use App\Http\Requests\StorePeripheralRequest;
use App\Http\Requests\UpdatePeripheralRequest;
use App\Models\Bay;
use App\Models\Building;
use App\Models\Document;
use App\Models\Entity;
use App\Models\MApplication;
use App\Models\Peripheral;
use App\Models\Site;
use Gate;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PeripheralController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('peripheral_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripherals = Peripheral::with(['site', 'building', 'bay', 'provider'])->orderBy('name')->get();

        return view('admin.peripherals.index', compact('peripherals'));
    }

    public function create()
    {
        abort_if(Gate::denies('peripheral_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');
        $icons = Peripheral::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // lists
        $type_list = Peripheral::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domain_list = Peripheral::select('domain')->where('domain', '<>', null)->distinct()->orderBy('domain')->pluck('domain');
        $responsible_list = Peripheral::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');

        return view(
            'admin.peripherals.create',
            compact(
                'sites',
                'buildings',
                'bays',
                'entities',
                'applications',
                'icons',
                'type_list',
                'domain_list',
                'responsible_list'
            )
        );
    }

    public function clone(Request $request)
    {
        abort_if(Gate::denies('peripheral_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');
        $icons = Peripheral::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // lists
        $type_list = Peripheral::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domain_list = Peripheral::select('domain')->where('domain', '<>', null)->distinct()->orderBy('domain')->pluck('domain');
        $responsible_list = Peripheral::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');

        // Get Peripheral
        $peripheral = Peripheral::find($request->id);

        // Vlan not found
        abort_if($peripheral === null, Response::HTTP_NOT_FOUND, '404 Not Found');

        $request->merge($peripheral->only($peripheral->getFillable()));
        $request->merge(['applications' => $peripheral->applications()->pluck('id')->unique()->toArray()]);
        $request->flash();

        return view(
            'admin.peripherals.create',
            compact(
                'sites',
                'buildings',
                'bays',
                'entities',
                'applications',
                'icons',
                'type_list',
                'domain_list',
                'responsible_list'
            )
        );
    }

    public function store(StorePeripheralRequest $request)
    {
        // Get fields
        $peripheral = Peripheral::create($request->all());

        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $peripheral->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $peripheral->icon_id = intval($request->iconSelect);
        } else {
            $peripheral->icon_id = null;
        }
        $peripheral->save();

        // Save links
        $peripheral->applications()->sync($request->input('applications', []));

        // Redirect
        return redirect()->route('admin.peripherals.index');
    }

    public function edit(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $sites = Site::all()->sortBy('name')->pluck('name', 'id');
        $buildings = Building::all()->sortBy('name')->pluck('name', 'id');
        $bays = Bay::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');
        $applications = MApplication::all()->sortBy('name')->pluck('name', 'id');
        $icons = Peripheral::select('icon_id')->whereNotNull('icon_id')->orderBy('icon_id')->distinct()->pluck('icon_id');

        // lists
        $type_list = Peripheral::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $domain_list = Peripheral::select('domain')->where('domain', '<>', null)->distinct()->orderBy('domain')->pluck('domain');
        $responsible_list = Peripheral::select('responsible')->where('responsible', '<>', null)->distinct()->orderBy('responsible')->pluck('responsible');

        $peripheral->load('site', 'building', 'bay');

        return view(
            'admin.peripherals.edit',
            compact(
                'sites',
                'buildings',
                'bays',
                'entities',
                'applications',
                'icons',
                'peripheral',
                'type_list',
                'domain_list',
                'responsible_list'
            )
        );
    }

    public function update(UpdatePeripheralRequest $request, Peripheral $peripheral)
    {
        // Save icon
        if (($request->files !== null) && $request->file('iconFile') !== null) {
            $file = $request->file('iconFile');
            // Create a new document
            $document = new Document();
            $document->filename = $file->getClientOriginalName();
            $document->mimetype = $file->getClientMimeType();
            $document->size = $file->getSize();
            $document->hash = hash_file('sha256', $file->path());

            // Save the document
            $document->save();

            // Move the file to storage
            $file->move(storage_path('docs'), $document->id);

            $peripheral->icon_id = $document->id;
        } elseif (preg_match('/^\d+$/', $request->iconSelect)) {
            $peripheral->icon_id = intval($request->iconSelect);
        } else {
            $peripheral->icon_id = null;
        }

        // Get fields
        $peripheral->update($request->all());

        // Update links
        $peripheral->applications()->sync($request->input('applications', []));

        return redirect()->route('admin.peripherals.index');
    }

    public function show(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->load('site', 'building', 'bay');

        return view('admin.peripherals.show', compact('peripheral'));
    }

    public function destroy(Peripheral $peripheral)
    {
        abort_if(Gate::denies('peripheral_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $peripheral->delete();

        return redirect()->route('admin.peripherals.index');
    }

    public function massDestroy(MassDestroyPeripheralRequest $request)
    {
        Peripheral::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
