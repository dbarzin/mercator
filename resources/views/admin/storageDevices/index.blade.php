@extends('layouts.admin')
@section('content')
@can('storage_device_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.storage-devices.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.storageDevice.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.storageDevice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-StorageDevice">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.building') }}
                        </th>
                        <th>
                            {{ trans('cruds.storageDevice.fields.bay') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($storageDevices as $key => $storageDevice)
                        <tr data-entry-id="{{ $storageDevice->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.storage-devices.show', $storageDevice->id) }}">
                                    {{ $storageDevice->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $storageDevice->description ?? '' !!}
                            </td>
                            <td>
                                {{ $storageDevice->site->name ?? '' }}
                            </td>
                            <td>
                                {{ $storageDevice->building->name ?? '' }}
                            </td>
                            <td>
                                {{ $storageDevice->bay->name ?? '' }}
                            </td>
                            <td>
                                @can('storage_device_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.storage-devices.show', $storageDevice->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('storage_device_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.storage-devices.edit', $storageDevice->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('storage_device_delete')
                                    <form action="{{ route('admin.storage-devices.destroy', $storageDevice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('storage_device_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.storage-devices.massDestroy') }}",
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
    order: [[ 1, 'asc' ]],
    pageLength: 100, stateSave: true,
  });
  $('.datatable-StorageDevice:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection
