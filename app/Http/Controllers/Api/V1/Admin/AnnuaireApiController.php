<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Annuaire;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\StoreAnnuaireRequest;
use App\Http\Requests\UpdateAnnuaireRequest;
use App\Http\Resources\Admin\AnnuaireResource;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AnnuaireApiController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('annuaire_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AnnuaireResource(Annuaire::with(['zone_admin'])->get());
    }

    public function store(StoreAnnuaireRequest $request)
    {
        $annuaire = Annuaire::create($request->all());

        return (new AnnuaireResource($annuaire))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new AnnuaireResource($annuaire->load(['zone_admin']));
    }

    public function update(UpdateAnnuaireRequest $request, Annuaire $annuaire)
    {
        $annuaire->update($request->all());

        return (new AnnuaireResource($annuaire))
            ->response()
            ->setStatusCode(Response::HTTP_ACCEPTED);
    }

    public function destroy(Annuaire $annuaire)
    {
        abort_if(Gate::denies('annuaire_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $annuaire->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
