<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationBlockRequest;
use App\Http\Requests\StoreApplicationBlockRequest;
use App\Http\Requests\UpdateApplicationBlockRequest;
use App\Models\ApplicationBlock;
use App\Models\MApplication;
use Gate;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpFoundation\Response;

class ApplicationBlockController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('application_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationblocks = ApplicationBlock::all();

        return response()->json($applicationblocks);
    }

    public function store(StoreApplicationBlockRequest $request)
    {
        abort_if(Gate::denies('application_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock = ApplicationBlock::create($request->all());
        MApplication::whereIn('id', $request->input('applications', []))
            ->update(['application_block_id' => $applicationBlock->id]);

        return response()->json($applicationBlock, 201);
    }

    public function show(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return new JsonResource($applicationBlock);
    }

    public function update(UpdateApplicationBlockRequest $request, ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->update($request->all());

        if ($request->has('applications')) {
            MApplication::whereIn('id', $request->input('applications', []))
                ->update(['application_block_id' => $applicationBlock->id]);
        }

        return response()->json();
    }

    public function destroy(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->delete();

        return response()->json();
    }

    public function massDestroy(MassDestroyApplicationBlockRequest $request)
    {
        abort_if(Gate::denies('application_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        ApplicationBlock::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
