@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.auditLog.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Entity">
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
                </tr>
            </thead>
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
                        {{ $log->subject_type }}                        
                    </td>
                    <td>
                        <a href="{{ \App\AuditLog::subject_url($log->subject_type) }}/{{ $log->subject_id }}">
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
                </tr>
                @endforeach
            </thead>
        </table>
        <p>
        {{ $logs->links() }}
        </p>        
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: false,
    bPaginate: false,
    pageLength: 100, 
    stateSave: true,
    bSort:false,
    bLengthChange:false,
    bInfo:false,
  });
  let table = $('.datatable-Entity:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
})

</script>
@endsection
