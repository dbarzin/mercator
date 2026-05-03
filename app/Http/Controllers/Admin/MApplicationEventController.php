<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\MApplicationEvent;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MApplicationEventController extends Controller
{
    /**
     * Retourne les événements d'une application.
     *
     * @param  \Illuminate\Http\Request  $request  Doit contenir :
     *                                             - `id` (int): ID de l'application.
     */
    public function index(Request $request): JsonResponse
    {
        abort_if(Gate::denies('application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'id' => ['required', 'integer', 'exists:applications,id'],
        ]);

        $this->authorize('application_show', MApplicationEvent::class);

        $events = MApplicationEvent::with('user')
            ->where('application_id', $request->integer('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($events);
    }


    public function store(Request $request): JsonResponse
    {
        abort_if(Gate::denies('application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'application_id' => ['required', 'integer', 'exists:applications,id'],
            'message'          => ['required', 'string', 'max:2000'],
        ]);

        $application = Application::findOrFail($request->integer('application_id'));

        $event = new MApplicationEvent();
        $event->application()->associate($application);
        $event->user()->associate($request->user());
        $event->message = $request->string('message');
        $event->saveOrFail();

        return response()->json([
            'events' => $application->events()->with('user')->orderBy('created_at', 'desc')->get(),
        ], 201);
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        abort_if(Gate::denies('application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'application_id' => ['required', 'integer', 'exists:applications,id'],
        ]);

        $application = Application::findOrFail($request->integer('application_id'));

        $event = $application->events()->findOrFail($id);

        $event->delete();

        return response()->json([
            'events' => $application->events()->with('user')->orderBy('created_at', 'desc')->get(),
        ]);
    }
}