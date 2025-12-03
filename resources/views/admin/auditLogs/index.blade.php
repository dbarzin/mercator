@extends('layouts.admin')
@section('content')

    <div class="card">
        <div class="card-header">
            {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <th width="10">
                    </th>
                    <th>
                        id
                    </th>
                    <th>
                        {{ trans('cruds.auditLog.fields.description') }}
                    </th>
                    <th>
                        {{ trans('cruds.auditLog.fields.subject_type') }}
                    </th>
                    <th>
                        {{ trans('cruds.auditLog.fields.subject_id') }}
                    </th>
                    <th>
                        {{ trans('cruds.auditLog.fields.user_id') }}
                    </th>
                    <th>
                        {{ trans('cruds.auditLog.fields.host') }}
                    </th>
                    <th>
                        {{ trans('global.created_at') }}
                    </th>
                    <th>
                    </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($logs as $log)
                        <tr data-entry-id="{{ $log->id }}">
                            <td>
                            </td>
                            <td>
                                <a href="{{ route('admin.audit-logs.show', $log->id) }}">
                                    {{ $log->id }}
                                </a>
                            </td>
                            <td>
                                {{ $log->description }}
                            </td>
                            <td>
                                {{ Str::afterLast($log->subject_type, '\\') }}
                            </td>
                            <td>
                                <a href="{{ \Mercator\Core\Models\AuditLog::URL($log->subject_type, $log->subject_id) }}">
                                    {{ $log->subject_id }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('admin.users.show', $log->user_id) }}">
                                    {{ $log->name }}
                                </a>
                            </td>
                            <td>
                                {{ $log->host }}
                            </td>
                            <td>
                                {{ $log->created_at }}
                            </td>
                            <td nowrap>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.audit-logs.show', $log->id) }}">
                                    {{ trans('global.view') }}
                                </a>
                                <a class="btn btn-xs btn-secondary" href="{{ route('admin.audit-logs.history',
                                ['type' => $log->subject_type, 'id' => $log->subject_id]) }}">
                                    {{ trans('global.history') }}
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endsection

        @section('scripts')
            @parent
            <script>
                @include('partials.datatable', array(
                    'id' => '#dataTable',
                    'title' => trans("cruds.site.title_singular"),
                    'URL' => route('admin.sites.massDestroy'),
                    'canDelete' => false,
                    'order' => "[7, 'desc']",
                    'maxPageLength' => 1000
                ))
            </script>
@endsection
