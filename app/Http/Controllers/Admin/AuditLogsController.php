<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class AuditLogsController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('audit_log_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $logs = DB::table('audit_logs')
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
            ->join('users', 'users.id', '=', 'user_id')
            ->orderBy('audit_logs.id', 'desc')->paginate(100);

        return view('admin.auditLogs.index', ['logs' => $logs]);
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
