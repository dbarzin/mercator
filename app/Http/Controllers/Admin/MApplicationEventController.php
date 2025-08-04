<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MApplication;
use App\MApplicationEvent;
use App\User;
use Illuminate\Http\Request;

class MApplicationEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $id = $request->query('id');

        $events = MApplicationEvent::with('user')
            ->where('m_application_id', $id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($events);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
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
     * Display the specified resource.
     *
     * @param  \App\MApplicationEvent  $mApplicationEvent
     *
     * @return \Illuminate\Http\Response
     */
    public function show(MApplicationEvent $mApplicationEvent)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MApplicationEvent  $mApplicationEvent
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(MApplicationEvent $mApplicationEvent)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MApplicationEvent  $mApplicationEvent
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MApplicationEvent $mApplicationEvent)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id Id de l'évènement
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, int $id)
    {
        $application = MApplication::findOrFail($request->get('m_application_id'));
        MApplicationEvent::findOrFail($id)->delete();

        return response()->json(['events' => $application->events]);
    }
}
