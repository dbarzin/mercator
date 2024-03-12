<?php

namespace App\Http\Controllers\API;

use App\Annuaire;
use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyAnnuaireRequest;
use App\Http\Requests\StoreAnnuaireRequest;
use App\Http\Requests\UpdateAnnuaireRequest;
use App\Http\Resources\Admin\AnnuaireResource;
use Gate;
use Illuminate\Http\Response;

class AnnuaireController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('annuaire_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaires = Annuaire::all();

        return response()->json($annuaires);
    }

    public function store(StoreAnnuaireRequest $request)
    {
        abort_if(Gate::denies('annuaire_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire = Annuaire::create($request->all());

        return response()->json($annuaire, 201);
    }

    public function show(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AnnuaireResource($annuaire);
    }

    public function update(UpdateAnnuaireRequest $request, Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire->update($request->all());

        return response()->json();
    }

    public function destroy(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyAnnuaireRequest $request)
    {
        abort_if(Gate::denies('annuaire_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Annuaire::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
