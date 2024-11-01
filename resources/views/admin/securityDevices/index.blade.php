@extends('layouts.admin')
@section('content')
@can('security_device_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.security-devices.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.securityDevice.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.securityDevice.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-SecurityDevice">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.securityDevice.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.securityDevice.fields.description') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($securityDevices as $key => $securityDevice)
                        <tr data-entry-id="{{ $securityDevice->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.security-devices.show', $securityDevice->id) }}">
                                {{ $securityDevice->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $securityDevice->description ?? '' !!}
                            </td>
                            <td>
                                @can('security_device_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.security-devices.show', $securityDevice->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('security_device_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.security-devices.edit', $securityDevice->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('security_device_delete')
                                    <form action="{{ route('admin.security-devices.destroy', $securityDevice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('security_device_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.security-devices.massDestroy') }}",
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
    order: [[ 1 , 'desc' ]],
    pageLength: 100, stateSave: true,
  });
  let table = $('.datatable-SecurityDevice:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
