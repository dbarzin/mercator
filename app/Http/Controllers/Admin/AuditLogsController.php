<?php

namespace App\Http\Controllers\Admin;

use App\AuditLog;
use App\Http\Controllers\Controller;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

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
}
