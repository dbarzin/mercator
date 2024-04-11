@extends('layouts.admin')
@section('content')
@can('logical_flow_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.logical-flows.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.logicalFlow.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.logicalFlow.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-LogicalFlow">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.protocol') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.source_ip_range') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.source_port') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.dest_ip_range') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.dest_port') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logicalFlows as $logicalFlow)
                        <tr data-entry-id="{{ $logicalFlow->id }}">
                            <td>

                            </td>
                            <td>
                            <a href="{{ route('admin.logical-flows.show', $logicalFlow->id) }}">
                                {{ $logicalFlow->name ?? "NONAME" }}
                            </a>
                            </td>
                            <td>
                                {!! $logicalFlow->description !!}
                            </td>
                            <td>
                                {{ $logicalFlow->protocol }}
                            </td>
                            <td>
                                {{ $logicalFlow->source_ip_range }}
                            </td>
                            <td>
                                {{ $logicalFlow->source_port ?? "ANY"  }}
                            </td>
                            <td>
                                {{ $logicalFlow->dest_ip_range }}
                            </td>
                            <td>
                                {{ $logicalFlow->dest_port ?? "ANY" }}
                            </td>
                            <td>
                                @can('logical_flow_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.logical-flows.show', $logicalFlow->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('logical_flow_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.logical-flows.edit', $logicalFlow->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('logical_flow_delete')
                                    <form action="{{ route('admin.logical-flows.destroy', $logicalFlow->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('logical_flow_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.logical-flows.massDestroy') }}",
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
    "lengthMenu": [ 10, 50, 100, 500 ],
  });
  let table = $('.datatable-LogicalFlow:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
})
</script>
@endsection
