@extends('layouts.admin')
@section('content')
@can('data_processing_register_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.data-processings.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.dataProcessing.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.dataProcessing.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Activity">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.processes') }}
                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.applications') }}
                        </th>
                        <th>
                            {{ trans('cruds.dataProcessing.fields.information') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($processingRegister as $processing)
                        <tr data-entry-id="{{ $processing->id }}"
                            @if (
                                ($processing->description===null)||
                                ($processing->responsible===null)||
                                ($processing->purpose===null)||
                                ($processing->categories===null)||
                                ($processing->recipients===null)||
                                ($processing->transfert===null)||
                                ($processing->retention===null)
                                )
                                class="table-warning"
                            @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.data-processings.show', $processing->id) }}">
                                {{ $processing->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $processing->description !!}
                            </td>
                            <td>
                                @foreach($processing->processes as $p)
                                    <a href="{{ route('admin.processes.show', $p->id) }}">{{ $p->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($processing->applications as $app)
                                    <a href="{{ route('admin.applications.show', $app->id) }}">{{ $app->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($processing->informations as $info)
                                    <a href="{{ route('admin.information.show', $info->id) }}">{{ $info->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @can('data_processing_register_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.data-processings.show', $processing->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('data_processing_register_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.data-processings.edit', $processing->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('data_processing_register_delete')
                                    <form action="{{ route('admin.data-processings.destroy', $processing->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('data_processing_register_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.data-processings.massDestroy') }}",
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
  let table = $('.datatable-Activity:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
