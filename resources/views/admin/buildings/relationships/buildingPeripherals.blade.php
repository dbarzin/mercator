@can('peripheral_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.peripherals.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.peripheral.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.peripheral.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-buildingPeripherals">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.responsible') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.building') }}
                        </th>
                        <th>
                            {{ trans('cruds.peripheral.fields.bay') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($peripherals as $key => $peripheral)
                        <tr data-entry-id="{{ $peripheral->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $peripheral->id ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->name ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->type ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->responsible ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->site->name ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->building->name ?? '' }}
                            </td>
                            <td>
                                {{ $peripheral->bay->name ?? '' }}
                            </td>
                            <td>
                                @can('peripheral_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.peripherals.show', $peripheral->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('peripheral_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.peripherals.edit', $peripheral->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('peripheral_delete')
                                    <form action="{{ route('admin.peripherals.destroy', $peripheral->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('peripheral_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.peripherals.massDestroy') }}",
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
    pageLength: 100,
  });
  let table = $('.datatable-buildingPeripherals:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection