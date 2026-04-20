<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\MassDestroyBackupRequest;
use App\Http\Requests\MassStoreBackupRequest;
use App\Http\Requests\MassUpdateBackupRequest;
use App\Http\Requests\StoreBackupRequest;
use App\Http\Requests\UpdateBackupRequest;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Backup;
use Symfony\Component\HttpFoundation\Response;

class BackupController extends APIController
{
    protected string $modelClass = Backup::class;

    public function index(Request $request)
    {
        abort_if(Gate::denies('backup_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return $this->indexResource($request);
    }

    public function store(StoreBackupRequest $request)
    {
        abort_if(Gate::denies('backup_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $backup = Backup::query()->create($request->all());

        return response()->json($backup, Response::HTTP_CREATED);
    }

    public function show(Backup $backup)
    {
        abort_if(Gate::denies('backup_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($backup);
    }

    public function update(UpdateBackupRequest $request, Backup $backup)
    {
        abort_if(Gate::denies('backup_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $backup->update($request->all());

        return response()->json();
    }

    public function destroy(Backup $backup)
    {
        abort_if(Gate::denies('backup_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $backup->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyBackupRequest $request)
    {
        abort_if(Gate::denies('backup_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Backup::query()->whereIn('id', $request->input('ids', []))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function massStore(MassStoreBackupRequest $request)
    {
        $data       = $request->validated();
        $createdIds = [];

        $model    = new Backup();
        $attributes = $model->getFillable();

        foreach ($data['items'] as $item) {
            /** @var Backup $backup */
            $backup = Backup::query()->create($attributes);

            $createdIds[] = $backup->id;
        }

        return response()->json([
            'status' => 'ok',
            'count'  => count($createdIds),
            'ids'    => $createdIds,
        ], Response::HTTP_CREATED);
    }

    public function massUpdate(MassUpdateBackupRequest $request)
    {
        $data     = $request->validated();
        $model    = new Backup();
        $attributes = $model->getFillable();

        foreach ($data['items'] as $rawItem) {
            $id        = $rawItem['id'];

            /** @var Backup $backup */
            $backup = Backup::query()->findOrFail($id);
            
            if (! empty($attributes)) {
                $backup->update($attributes);
            }

        }

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
