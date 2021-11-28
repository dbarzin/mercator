@extends('layouts.admin')
@section('content')
@can('zone_admin_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.zone-admins.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.zoneAdmin.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.zoneAdmin.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-ZoneAdmin">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.zoneAdmin.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.zoneAdmin.fields.description') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zoneAdmins as $key => $zoneAdmin)
                        <tr data-entry-id="{{ $zoneAdmin->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $zoneAdmin->name ?? '' }}
                            </td>
                            <td>
                                {!! $zoneAdmin->description ?? '' !!}
                            </td>
                            <td>
                                @can('zone_admin_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.zone-admins.show', $zoneAdmin->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('zone_admin_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.zone-admins.edit', $zoneAdmin->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('zone_admin_delete')
                                    <form action="{{ route('admin.zone-admins.destroy', $zoneAdmin->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('zone_admin_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.zone-admins.massDestroy') }}",
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
  let table = $('.datatable-ZoneAdmin:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection