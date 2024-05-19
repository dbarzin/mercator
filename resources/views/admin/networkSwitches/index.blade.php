@extends('layouts.admin')
@section('content')
@can('network_switch_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.network-switches.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.networkSwitch.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.networkSwitch.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-NetworkSwitch">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.ip') }}
                        </th>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.physical_switches') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($networkSwitches as $key => $networkSwitch)
                        <tr data-entry-id="{{ $networkSwitch->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.network-switches.show', $networkSwitch->id) }}">
                                    {{ $networkSwitch->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $networkSwitch->description ?? '' !!}
                            </td>
                            <td>
                                {{ $networkSwitch->ip ?? '' }}
                            </td>
                            <td>
                                @foreach($networkSwitch->physicalSwitches as $physicalSwitch)
                                    <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">
                                    {{ $physicalSwitch->name }}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @can('network_switch_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.network-switches.show', $networkSwitch->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('network_switch_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.network-switches.edit', $networkSwitch->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('network_switch_delete')
                                    <form action="{{ route('admin.network-switches.destroy', $networkSwitch->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('network_switch_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.network-switches.massDestroy') }}",
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
  let table = $('.datatable-NetworkSwitch:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
