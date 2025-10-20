<?php


namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyApplicationBlockRequest;
use App\Http\Requests\StoreApplicationBlockRequest;
use App\Http\Requests\UpdateApplicationBlockRequest;
use App\Models\ApplicationBlock;
use App\Models\MApplication;
use App\Services\CartographerService;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class ApplicationBlockController extends Controller
{
    protected CartographerService $cartographerService;

    /**
     * Automatic Injection for Service
     *
     * @return void
     */
    public function __construct(CartographerService $cartographerService)
    {
        $this->cartographerService = $cartographerService;
    }

    public function index()
    {
        abort_if(Gate::denies('application_block_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // $applicationBlocks = ApplicationBlock::all();
        $applicationBlocks = ApplicationBlock::with('applications')->orderBy('name')->get();

        return view('admin.applicationBlocks.index', compact('applicationBlocks'));
    }

    public function create()
    {
        abort_if(Gate::denies('application_block_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::with('cartographers')->get();
        // Filtre sur les cartographes
        $applications = $this->cartographerService->filterOnCartographers($applications);

        return view('admin.applicationBlocks.create', compact('applications'));
    }

    public function store(StoreApplicationBlockRequest $request)
    {
        $applicationBlock = ApplicationBlock::create($request->all());

        MApplication::whereIn('id', $request->input('linkToApplications', []))
            ->update(['application_block_id' => $applicationBlock->id]);

        return redirect()->route('admin.application-blocks.index');
    }

    public function edit(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applications = MApplication::with('cartographers')->get();
        // Filtre sur les cartographes
        $applications = $this->cartographerService->filterOnCartographers($applications);
        $applicationBlock->load('applications');

        return view('admin.applicationBlocks.edit', compact('applicationBlock', 'applications'));
    }

    public function update(UpdateApplicationBlockRequest $request, ApplicationBlock $applicationBlock)
    {
        $applicationBlock->update($request->all());

        MApplication::where('application_block_id', $applicationBlock->id)
            ->update(['application_block_id' => null]);

        MApplication::whereIn('id', $request->input('linkToApplications', []))
            ->update(['application_block_id' => $applicationBlock->id]);

        return redirect()->route('admin.application-blocks.index');
    }

    public function show(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->load('applications');

        return view('admin.applicationBlocks.show', compact('applicationBlock'));
    }

    public function destroy(ApplicationBlock $applicationBlock)
    {
        abort_if(Gate::denies('application_block_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $applicationBlock->delete();

        return redirect()->route('admin.application-blocks.index');
    }

    public function massDestroy(MassDestroyApplicationBlockRequest $request)
    {
        ApplicationBlock::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
