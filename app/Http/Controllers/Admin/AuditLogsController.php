<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mercator\Core\Models\AuditLog;
use Symfony\Component\HttpFoundation\Response;

class AuditLogsController extends Controller
{


    public function index(Request $request)
    {
        abort_if(Gate::denies('audit_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $search   = $request->input('search');
        $perPage  = (int) $request->input('per_page', 100);

        // On borne les valeurs possibles pour éviter les conneries
        $allowedPerPage = [10, 25, 50, 100, 1000];
        if (! in_array($perPage, $allowedPerPage)) {
            $perPage = 100;
        }

        $query = DB::table('audit_logs')
            ->select(
                'audit_logs.id',
                'description',
                'subject_type',
                'subject_id',
                'users.name',
                'user_id',
                'host',
                'audit_logs.created_at'
            )
            ->join('users', 'users.id', '=', 'user_id');

        if (!empty($search)) {

            // Découpe la recherche en mots séparés
            $terms = preg_split('/\s+/', trim($search));

            // Pour chaque mot, on impose une condition (AND)
            foreach ($terms as $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('description', 'like', "%{$term}%")
                        ->orWhere('properties', 'like', "%{$term}%")
                        ->orWhere('subject_type', 'like', "%{$term}%")
                        ->orWhere('users.name', 'like', "%{$term}%")
                        ->orWhere('host', 'like', "%{$term}%");
                });
            }
        }

        $logs = $query
            ->orderBy('audit_logs.id', 'desc')
            ->paginate($perPage)
            ->appends([
                'search'   => $search,
                'per_page' => $perPage,
            ]);

        return view('admin.auditLogs.index', [
            'logs'      => $logs,
            'search'    => $search,
            'perPage'   => $perPage,
            'perPageOptions' => $allowedPerPage,
        ]);
    }



    public function show(AuditLog $auditLog)
    {
        abort_if(Gate::denies('audit_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.auditLogs.show', compact('auditLog'));
    }

    public function history(Request $request)
    {
        abort_if(Gate::denies('audit_log_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        abort_if(($request->id === null) || ($request->type === null), 500, '500 missing parameters');

        // Get the list
        $auditLogs =
            DB::table('audit_logs')
                ->select(
                    'audit_logs.id',
                    'description',
                    'subject_type',
                    'subject_id',
                    'users.name',
                    'user_id',
                    'host',
                    'properties',
                    'audit_logs.created_at'
                )
                ->join('users', 'users.id', '=', 'user_id')
                ->where('subject_id', $request->id)
                ->where('subject_type', $request->type)
                ->orderBy('audit_logs.id')
                ->get();

        abort_if($auditLogs->isEmpty(), 404, 'Not found');

        // JSON decode all properties
        foreach ($auditLogs as $auditLog) {
            $auditLog->properties = json_decode(trim(stripslashes($auditLog->properties), '"'));
        }

        return view('admin.auditLogs.history', compact('auditLogs'));
    }
}
