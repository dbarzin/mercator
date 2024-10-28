@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.audit-logs.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.auditLog.title') }}
    </div>

    <div class="card-body table-responsive">
        <table class="table table-bordered table-striped" style="table-layout: fixed; width: {{ $auditLogs->count() * 300 + 150 }}px;">
            <tbody>
                <tr>
                    <th style="width: 150px;">
                        {{ trans('cruds.auditLog.fields.subject_id') }}
                    </th>
                    <td>
                        <a href="{{ \App\AuditLog::subject_url($auditLogs->first()->subject_type) }}/{{ $auditLogs->first()->subject_id }}">
                            {{ $auditLogs->first()->subject_id }}
                        </a>
                    </td>
                </tr>
                <tr>
                    <th>
                        id
                    </th>
                    @foreach($auditLogs as $auditLog)
                    <td>
                    <a href="{{ route('admin.audit-logs.show', $auditLog->id) }}">
                        {{ $auditLog->id }}
                    </a>
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.auditLog.fields.description') }}
                    </th>
                    @foreach($auditLogs as $auditLog)
                    <td>
                    {{ $auditLog->description }}
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.auditLog.fields.user_id') }}
                    </th>
                    @foreach($auditLogs as $auditLog)
                    <td>
                    <a href="{{ route('admin.users.show', $auditLog->user_id) }}">
                        {{ $auditLog->name }}
                    </a>
                    </td>
                    @endforeach
                </tr>
                @foreach($auditLog->properties as $key => $value)
                <tr>
                        <th>[{{ $key }}]</th>
                        @php $previous = null; @endphp
                        @foreach($auditLogs as $auditLog2)
                            @php
                                $value = $auditLog2->properties->{$key};
                            @endphp
                        <td {!! (($loop->first)||($value==$previous)) ? "" : "class='bg-success'" !!}>
                            @if ((gettype($value)=="string")&&(strlen($value)>100))
                            {{ substr($value,0,100) }}
                            @else
                            {{ $value }}
                            @endif
                        </td>
                            @php $previous = $value; @endphp
                        @endforeach
                </tr>
                @endforeach
                <tr>
                    <th>
                        {{ trans('cruds.auditLog.fields.host') }}
                    </th>
                    @foreach($auditLogs as $auditLog)
                    <td>
                        {{ $auditLog->host }}
                    </td>
                    @endforeach
                </tr>
                <tr>
                    <th>
                        {{ trans('global.created_at') }}
                    </th>
                    @foreach($auditLogs as $auditLog)
                    <td>
                        {{ $auditLog->created_at }}
                    </td>
                    @endforeach
                </tr>
            </tbody>
        </table>
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.audit-logs.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
@section('styles')
    <style>
        /* Style to enable horizontal scroll */
        .table-responsive {
            overflow-x: auto;
        }
    </style>
@endsection
