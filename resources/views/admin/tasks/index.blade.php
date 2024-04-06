@extends('layouts.admin')
@section('content')
@can('task_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.tasks.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.task.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.task.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Task">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.task.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.task.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.task.fields.operations') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $key => $task)
                        <tr data-entry-id="{{ $task->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.tasks.show', $task->id) }}">
                                {{ $task->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $task->description ?? '' !!}
                            </td>
                            <td>
                                @foreach($task->operations as $operation)
                                    <a href="{{ route('admin.operations.show', $operation->id) }}">
                                        {{ $operation->name }}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @can('task_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.tasks.show', $task->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('task_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.tasks.edit', $task->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('task_delete')
                                    <form action="{{ route('admin.tasks.destroy', $task->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('task_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tasks.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'asc' ]],
    pageLength: 100, stateSave: true,
  });
  let table = $('.datatable-Task:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
