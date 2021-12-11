@can('physical_security_device_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.physical-security-devices.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.physicalSecurityDevice.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.physicalSecurityDevice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-bayPhysicalSecurityDevices">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.building') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.bay') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($physicalSecurityDevices as $key => $physicalSecurityDevice)
                        <tr data-entry-id="{{ $physicalSecurityDevice->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $physicalSecurityDevice->id ?? '' }}
                            </td>
                            <td>
                                {{ $physicalSecurityDevice->name ?? '' }}
                            </td>
                            <td>
                                {{ $physicalSecurityDevice->type ?? '' }}
                            </td>
                            <td>
                                {{ $physicalSecurityDevice->site->name ?? '' }}
                            </td>
                            <td>
                                {{ $physicalSecurityDevice->building->name ?? '' }}
                            </td>
                            <td>
                                {{ $physicalSecurityDevice->bay->name ?? '' }}
                            </td>
                            <td>
                                @can('physical_security_device_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.physical-security-devices.show', $physicalSecurityDevice->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('physical_security_device_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.physical-security-devices.edit', $physicalSecurityDevice->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('physical_security_device_delete')
                                    <form action="{{ route('admin.physical-security-devices.destroy', $physicalSecurityDevice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('physical_security_device_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.physical-security-devices.massDestroy') }}",
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
  let table = $('.datatable-bayPhysicalSecurityDevices:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection