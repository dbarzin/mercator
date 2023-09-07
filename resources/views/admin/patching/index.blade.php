@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-2">
        <form method="get" action="/admin/patching/index">
            <label class="recommended" for="patching_group">{{ trans('cruds.logicalServer.fields.patching_group') }}</label>
            <select name="group" class="form-control select2 {{ $errors->has('patching_group') ? 'is-invalid' : '' }}" 
                    name="patching_group" id="patching_group"
                    onchange="this.form.submit()">
                <option value="None"></option>
                @foreach($patching_group_list as $group)
                    <option {{ session()->get('patching_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('panel.menu.patching') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-LogicalServer">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.operating_system') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.applications') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.responsible') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.patching_group') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.update_date') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalServer.fields.next_update') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($servers as $server)
                        <tr data-entry-id="{{ $server->id }}">  
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.logical-servers.show', $server->id) }}">
                                {{ $server->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $server->operating_system ?? '' }}
                            </td>
                            <td>
                              @foreach($server->applications as $application)
                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                  {{ $application->name }}
                                </a>
                                  @if(!$loop->last)
                                  ,
                                  @endif
                              @endforeach
                            </td>
                            <td>
                              @foreach($server->applications as $application)
                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                  {{ $application->responsible }}
                                </a>
                                  @if(!$loop->last)
                                  ,
                                  @endif
                              @endforeach
                            </td>
                            <td>
                                {{ $server->patching_group ?? '' }}
                            </td>
                            <td>
                                {{ $server->update_date ?? '' }}
                            </td>
                            <td>
                                {{ $server->next_update ?? '' }}
                            </td>
                            <td>
                                @can('logical_server_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.patching.edit', $server->id) }}">
                                        {{ trans('global.patch') }}
                                    </a>
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
@can('logical_server_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.logical-servers.massDestroy') }}",
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
  let table = $('.datatable-LogicalServer:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
