@extends('layouts.admin')
@section('content')
@can('vlan_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.vlans.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.vlan.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.vlan.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Vlan">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.vlan.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.vlan.fields.vlan_id') }}
                        </th>
                        <th>
                            {{ trans('cruds.vlan.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.vlan.fields.subnetworks') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vlans as $key => $vlan)
                        <tr data-entry-id="{{ $vlan->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.vlans.show', $vlan->id) }}">
                                {{ $vlan->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $vlan->vlan_id }}
                            </td>
                            <td>
                                {{ $vlan->description ?? '' }}
                            </td>
                            <td>
                                @foreach($vlan->subnetworks as $subnetwork)
                                    <a href="{{ route('admin.subnetworks.show', $subnetwork->id) }}">
                                    {{$subnetwork->name}}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @can('vlan_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.vlans.show', $vlan->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('vlan_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.vlans.edit', $vlan->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('vlan_delete')
                                    <form action="{{ route('admin.vlans.destroy', $vlan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('vlan_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.vlans.massDestroy') }}",
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
    "lengthMenu": [ 10, 50, 100, 500 ]
  });
  let table = $('.datatable-Vlan:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
