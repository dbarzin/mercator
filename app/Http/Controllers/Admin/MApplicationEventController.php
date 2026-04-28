<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MApplication;
use App\Models\MApplicationEvent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
        $request->validate([
            'id' => ['required', 'integer', 'exists:m_applications,id'],
        ]);

        $this->authorize('m_application_show', MApplicationEvent::class);

        $events = MApplicationEvent::with('user')
            ->where('m_application_id', $request->integer('id'))
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($events);
    }

    /**
     * Crée un événement pour une application et retourne la liste mise à jour.
     *
     * @param  \Illuminate\Http\Request  $request  Doit contenir :
     *                                             - `m_application_id` (int): ID de l'application.
     *                                             - `message` (string): Message de l'événement.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si l'application est introuvable.
     * @throws \Throwable                                           Si la sauvegarde échoue.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'm_application_id' => ['required', 'integer', 'exists:m_applications,id'],
            'message'          => ['required', 'string', 'max:2000'],
        ]);

        $application = MApplication::findOrFail($request->integer('m_application_id'));

        $this->authorize('m_application_create', [MApplicationEvent::class, $application]);

        $event = new MApplicationEvent();
        $event->application()->associate($application);
        // Toujours utiliser l'utilisateur authentifié — ne jamais accepter user_id du client.
        $event->user()->associate($request->user());
        $event->message = $request->string('message');
        $event->saveOrFail();

        return response()->json([
            'events' => $application->events()->with('user')->orderBy('created_at', 'desc')->get(),
        ], 201);
    }

    /**
     * Supprime un événement d'une application et retourne la liste mise à jour.
     *
     * @param  \Illuminate\Http\Request  $request  Doit contenir :
     *                                             - `m_application_id` (int): ID de l'application.
     * @param  int                       $id        ID de l'événement à supprimer.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException Si l'application ou l'événement est introuvable.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        $request->validate([
            'm_application_id' => ['required', 'integer', 'exists:m_applications,id'],
        ]);

        $application = MApplication::findOrFail($request->integer('m_application_id'));

        // Recherche scopée à l'application pour éviter la suppression cross-application.
        $event = $application->events()->findOrFail($id);

        $this->authorize('m_application_delete', $event);

        $event->delete();

        return response()->json([
            'events' => $application->events()->with('user')->orderBy('created_at', 'desc')->get(),
        ]);
    }
}