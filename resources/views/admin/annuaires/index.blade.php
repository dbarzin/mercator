@extends('layouts.admin')
@section('content')
@can('annuaire_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.annuaires.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.annuaire.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.annuaire.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Annuaire">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.annuaire.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.annuaire.fields.solution') }}
                        </th>
                        <th>
                            {{ trans('cruds.annuaire.fields.zone_admin') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($annuaires as $key => $annuaire)
                        <tr data-entry-id="{{ $annuaire->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $annuaire->name ?? '' }}
                            </td>
                            <td>
                                {{ $annuaire->solution ?? '' }}
                            </td>
                            <td>
                                {{ $annuaire->zone_admin->name ?? '' }}
                            </td>

                            <td>
                                @can('annuaire_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.annuaires.show', $annuaire->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('annuaire_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.annuaires.edit', $annuaire->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('annuaire_delete')
                                    <form action="{{ route('admin.annuaires.destroy', $annuaire->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('annuaire_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.annuaires.massDestroy') }}",
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
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-Annuaire:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection