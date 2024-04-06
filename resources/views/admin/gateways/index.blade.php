@extends('layouts.admin')
@section('content')
@can('gateway_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.gateways.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.gateway.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.gateway.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Gateway">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.authentification') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.ip') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gateways as $key => $gateway)
                        <tr data-entry-id="{{ $gateway->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $gateway->name ?? '' }}
                            </td>
                            <td>
                                {!! $gateway->description ?? '' !!}
                            </td>
                            <td>
                                {{ $gateway->authentification ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ip ?? '' }}
                            </td>
                            <td>
                                @can('gateway_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.gateways.show', $gateway->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('gateway_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.gateways.edit', $gateway->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('gateway_delete')
                                    <form action="{{ route('admin.gateways.destroy', $gateway->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('gateway_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.gateways.massDestroy') }}",
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
    order: [[ 2, 'desc' ]],
    pageLength: 100, stateSave: true,
  });
  let table = $('.datatable-Gateway:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
