<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MApplication;
use App\Models\MApplicationEvent;
use App\Models\User;
use Illuminate\Http\Request;

class MApplicationEventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): \Illuminate\Http\Response
    {
        $id = $request->query('id');

        $events = MApplicationEvent::with('user')
            ->where('m_application_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($events);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): \Illuminate\Http\JsonResponse
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
    public function destroy(Request $request, int $id): \Illuminate\Http\JsonResponse
    {
        $application = MApplication::findOrFail($request->get('m_application_id'));
        MApplicationEvent::findOrFail($id)->delete();

        return response()->json(['events' => $application->events]);
    }
}
