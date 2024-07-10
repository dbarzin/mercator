@extends('layouts.admin')
@section('content')
@can('admin_user_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.admin-users.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.adminUser.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.adminUser.title') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-AdminUser">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.adminUser.fields.user_id') }}
                        </th>
                        <th>
                            {{ trans('cruds.adminUser.fields.lastname') }}
                        </th>
                        <th>
                            {{ trans('cruds.adminUser.fields.firstname') }}
                        </th>
                        <th>
                            {{ trans('cruds.adminUser.fields.domain') }}
                        </th>
                        <th>
                            {{ trans('cruds.adminUser.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.adminUser.fields.description') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $key => $user)
                        <tr data-entry-id="{{ $user->id }}"
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.admin-users.show', $user->id) }}">
                                {{ $user->user_id ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $user->firstname ?? '' }}
                            </td>
                            <td>
                                {{ $user->lastname ?? '' }}
                            </td>
                            <td>
                                {{ $user->domain->name ?? '' }}
                            </td>
                            <td>
                                {{ $user->type ?? '' }}
                            </td>
                            <td>
                                {!! $user->description !!}
                            </td>
                            <td>
                                @can('admin_user_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.admin-users.show', $user->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('admin_user_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.admin-users.edit', $user->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('admin_user_delete')
                                    <form action="{{ route('admin.admin-users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('admin_user_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.admin-users.massDestroy') }}",
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
  let table = $('.datatable-AdminUser:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
})
</script>
@endsection
