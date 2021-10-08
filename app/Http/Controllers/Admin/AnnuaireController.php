<?php

namespace App\Http\Controllers\Admin;

use App\Annuaire;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAnnuaireRequest;
use App\Http\Requests\StoreAnnuaireRequest;
use App\Http\Requests\UpdateAnnuaireRequest;
use App\ZoneAdmin;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class AnnuaireController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('annuaire_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaires = Annuaire::all()->sortBy('name');

        return view('admin.annuaires.index', compact('annuaires'));
    }

    public function create()
    {
        abort_if(Gate::denies('annuaire_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zone_admins = ZoneAdmin::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.annuaires.create', compact('zone_admins'));
    }

    public function store(StoreAnnuaireRequest $request)
    {
        $annuaire = Annuaire::create($request->all());

        return redirect()->route('admin.annuaires.index');
    }

    public function edit(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $zone_admins = ZoneAdmin::all()->sortBy('name')->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $annuaire->load('zone_admin');

        return view('admin.annuaires.edit', compact('zone_admins', 'annuaire'));
    }

    public function update(UpdateAnnuaireRequest $request, Annuaire $annuaire)
    {
        $annuaire->update($request->all());

        return redirect()->route('admin.annuaires.index');
    }

    public function show(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire->load('zone_admin');

        return view('admin.annuaires.show', compact('annuaire'));
    }

    public function destroy(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire->delete();

        return redirect()->route('admin.annuaires.index');
    }

    public function massDestroy(MassDestroyAnnuaireRequest $request)
    {
        Annuaire::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
