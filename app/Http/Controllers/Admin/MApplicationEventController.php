<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MApplication;
use App\Models\MApplicationEvent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MApplicationEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $id = $request->query('id');

        $events = MApplicationEvent::with('user')
            ->where('m_application_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($events);
    }

    /**
     * Create a new event for an application and return the application's events.
     *
     * Expects the request to provide the application ID, user ID, and event message.
     *
     * @param \Illuminate\Http\Request $request Request containing:
     *     - `m_application_id` (int): ID of the application to attach the event to.
     *     - `user_id` (int): ID of the user who created the event.
     *     - `message` (string): The event message.
     * @return \Illuminate\Http\JsonResponse JSON with an `events` key containing the application's events.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the application or user cannot be found.
     */
    public function store(Request $request): JsonResponse
    {
        $application = MApplication::findOrFail($request->get('m_application_id'));
        $user = User::findOrFail($request->get('user_id'));
        $event = new MApplicationEvent();
        $event->application()->associate($application);
        $event->user()->associate($user);
        $event->message = $request->get('message');
        $event->saveOrFail();

        return response()->json(['events' => $application->events]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id  Id de l'évènement
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $application = MApplication::findOrFail($request->get('m_application_id'));
        MApplicationEvent::findOrFail($id)->delete();

        return response()->json(['events' => $application->events]);
    }
}