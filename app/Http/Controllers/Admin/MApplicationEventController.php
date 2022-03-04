<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\MApplicationEvent;
use App\User;
use App\MApplication;
use http\Env\Response;
use Illuminate\Http\Request;

class MApplicationEventController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
        $application->load(['events' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        // On veut le nom utilisateur pour chaque évènements
        foreach($application->events as $event) {
            $event->load(['user' => function($query) {
                $query->select('id', 'name');
            }]);
        }

        return response()->json(['events' => $application->events]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\MApplicationEvent  $mApplicationEvent
     * @return \Illuminate\Http\Response
     */
    public function show(MApplicationEvent $mApplicationEvent)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\MApplicationEvent  $mApplicationEvent
     * @return \Illuminate\Http\Response
     */
    public function edit(MApplicationEvent $mApplicationEvent)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\MApplicationEvent  $mApplicationEvent
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MApplicationEvent $mApplicationEvent)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\MApplicationEvent  $mApplicationEvent
     * @return \Illuminate\Http\Response
     */
    public function destroy(MApplicationEvent $mApplicationEvent)
    {
        //
    }
}
