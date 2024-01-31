@extends('layouts.admin')
@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-9">
    <table>
        <tr>
            <td>
        <br>
        <form method="get" action="/admin/patching/dashboard">
            <button class="btn btn-primary" type="submit">
                Dashboard
            </button>
        </form>
    </td>
    <td>
        &nbsp;
    </td>
    <td>
        <form method="get" action="/admin/patching/index">
            <label class="recommended" for="patching_group">{{ trans('cruds.logicalServer.fields.patching_group') }}</label>
            <select name="group" class="form-control select2"
                    name="patching_group" id="patching_group"
                    onchange="this.form.submit()">
                <option value="NONE">&nbsp;</option>
                @foreach($patching_group_list as $group)
                    <option {{ session()->get('patching_group') == $group ? 'selected' : '' }}>{{ $group }}</option>
                @endforeach
            </select>
        </form>
    </td></tr></table>
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
                        <tr data-entry-id="{{ $server->id }}"
                            @if ($server->next_update===null)
                                class="table-secondary"
                            @elseif (Carbon\Carbon::createFromFormat('d/m/Y',$server->next_update)->lt(today()))
                                class="table-danger"
                            @else
                                class="table-success"
                            @endif
                            >
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
                                @php($responsibles = array())
                                @foreach($server->applications as $application)
                                    @if ($application->responsible!=null)
                                        @foreach(explode(",",$application->responsible) as $responsible)
                                            @if (!in_array($responsible, $responsibles))
                                                @php(array_push($responsibles, $responsible))
                                            @endif
                                        @endforeach
                                     @endif
                                @endforeach
                                @foreach($responsibles as $responsible)
                                    {{ $responsible }}
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                {{ $server->patching_group ?? '' }}
                            </td>
                            <td>
                                {{ $server->update_date!=null ? Carbon\Carbon::createFromFormat('d/m/Y',$server->update_date)->format("Y-m-d") : ""}}
                            </td>
                            <td>
                                {{ $server->next_update!=null ? Carbon\Carbon::createFromFormat('d/m/Y',$server->next_update)->format("Y-m-d") : ""}}
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
