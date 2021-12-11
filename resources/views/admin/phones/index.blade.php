@extends('layouts.admin')
@section('content')
@can('phone_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.phones.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.phone.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.phone.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Phone">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.phone.fields.building') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($phones as $key => $phone)
                        <tr data-entry-id="{{ $phone->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $phone->name ?? '' }}
                            </td>
                            <td>
                                {{ $phone->type ?? '' }}
                            </td>
                            <td>
                                {{ $phone->site->name ?? '' }}
                            </td>
                            <td>
                                {{ $phone->building->name ?? '' }}
                            </td>
                            <td>
                                @can('phone_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.phones.show', $phone->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('phone_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.phones.edit', $phone->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('phone_delete')
                                    <form action="{{ route('admin.phones.destroy', $phone->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('phone_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.phones.massDestroy') }}",
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
  $('.datatable-Phone:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection