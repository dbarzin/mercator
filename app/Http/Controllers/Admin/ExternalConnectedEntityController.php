<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyExternalConnectedEntityRequest;
use App\Http\Requests\StoreExternalConnectedEntityRequest;
use App\Http\Requests\UpdateExternalConnectedEntityRequest;
use App\Models\Cartographer;
use App\Models\Entity;
use App\Models\ExternalConnectedEntity;
use App\Models\Network;
use App\Models\Subnetwork;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ExternalConnectedEntityController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $allowedIds = Gate::allows('external_connected_entity_access') ? null : Cartographer::allowedIdsFor($user, ExternalConnectedEntity::class);
        if ($allowedIds !== null && empty($allowedIds)) {
            abort(Response::HTTP_FORBIDDEN, '403 Forbidden');
        }

        $externalConnectedEntities = ExternalConnectedEntity::query()
            ->when(request('search'), function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    foreach (ExternalConnectedEntity::$searchable as $field) {
                        $q->orWhereRaw('LOWER('.$field.') LIKE ?', ['%'.mb_strtolower($search).'%']);
                    }
                });
            })
            ->orderBy('name')
            ->when($allowedIds !== null, fn ($q) => $q->whereIn('id', $allowedIds))->paginate(min(max((int) request('per_page', 50), 10), 500));

        return view('admin.externalConnectedEntities.index', compact('externalConnectedEntities'));
    }

    public function create()
    {
        abort_if(Gate::denies('external_connected_entity_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networks = Network::all()->sortBy('name')->pluck('name', 'id');
        $subnetworks = Subnetwork::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');

        $type_list = ExternalConnectedEntity::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Clear documents from session
        session()->put('documents', []);

        return view(
            'admin.externalConnectedEntities.create',
            compact('networks', 'subnetworks', 'entities', 'type_list', 'attributes_list')
        );
    }

    public function store(StoreExternalConnectedEntityRequest $request)
    {
        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $externalConnectedEntity = ExternalConnectedEntity::create($request->all());
        $externalConnectedEntity->subnetworks()->sync($request->input('subnetworks', []));

        // Documents
        $externalConnectedEntity->documents()->sync(session()->get('documents'));
        session()->forget('documents');

        return redirect()->route('admin.external-connected-entities.index');
    }

    public function edit(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('edit-object', $externalConnectedEntity), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $networks = Network::all()->sortBy('name')->pluck('name', 'id');
        $subnetworks = Subnetwork::all()->sortBy('name')->pluck('name', 'id');
        $entities = Entity::all()->sortBy('name')->pluck('name', 'id');

        $type_list = ExternalConnectedEntity::select('type')->where('type', '<>', null)->distinct()->orderBy('type')->pluck('type');
        $attributes_list = $this->getAttributes();

        // Get Documents
        $documents = [];
        foreach ($externalConnectedEntity->documents as $doc) {
            array_push($documents, $doc->id);
        }
        session()->put('documents', $documents);

        return view(
            'admin.externalConnectedEntities.edit',
            compact('externalConnectedEntity', 'networks', 'subnetworks', 'entities', 'type_list', 'attributes_list')
        );
    }

    public function update(UpdateExternalConnectedEntityRequest $request, ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('edit-object', $externalConnectedEntity), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request['attributes'] = implode(' ', $request->get('attributes') !== null ? $request->get('attributes') : []);

        $externalConnectedEntity->update($request->all());
        $externalConnectedEntity->subnetworks()->sync($request->input('subnetworks', []));

        $externalConnectedEntity->documents()->sync(session()->get('documents'));
        session()->forget('documents');

        return redirect()->route('admin.external-connected-entities.index');
    }

    public function show(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('show-object', $externalConnectedEntity), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.externalConnectedEntities.show', compact('externalConnectedEntity'));
    }

    public function destroy(ExternalConnectedEntity $externalConnectedEntity)
    {
        abort_if(Gate::denies('external_connected_entity_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $externalConnectedEntity->delete();

        return redirect()->route('admin.external-connected-entities.index');
    }

    public function massDestroy(MassDestroyExternalConnectedEntityRequest $request)
    {
        ExternalConnectedEntity::whereIn('id', request('ids'))->get()->each->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    private function getAttributes()
    {
        $attributes_list = ExternalConnectedEntity::query()
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
