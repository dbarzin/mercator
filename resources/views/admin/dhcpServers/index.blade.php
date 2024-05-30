@extends('layouts.admin')
@section('content')
@can('dhcp_server_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.dhcp-servers.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.dhcpServer.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.dhcpServer.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-DhcpServer">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.dhcpServer.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.dhcpServer.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.dhcpServer.fields.address_ip') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($dhcpServers as $key => $dhcpServer)
                        <tr data-entry-id="{{ $dhcpServer->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.dhcp-servers.show', $dhcpServer->id) }}">                                
                                {{ $dhcpServer->name ?? '' }}
                            </td>
                            <td>
                                {!! $dhcpServer->description !!}
                            </td>
                            <td>
                                {{ $dhcpServer->address_ip ?? '' }}
                            </td>
                            <td>
                                @can('dhcp_server_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.dhcp-servers.show', $dhcpServer->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('dhcp_server_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.dhcp-servers.edit', $dhcpServer->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('dhcp_server_delete')
                                    <form action="{{ route('admin.dhcp-servers.destroy', $dhcpServer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('dhcp_server_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.dhcp-servers.massDestroy') }}",
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
  let table = $('.datatable-DhcpServer:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
