@extends('layouts.admin')
@section('content')
@can('relation_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.relations.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.relation.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.relation.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Relation">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.relation.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.relation.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.relation.fields.importance') }}
                        </th>
                        <th>
                            {{ trans('cruds.relation.fields.source') }}
                        </th>
                        <th>
                            {{ trans('cruds.relation.fields.destination') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($relations as $key => $relation)
                        <tr data-entry-id="{{ $relation->id }}"

                        @if (
                            ($relation->description==null)||
                            ($relation->importance==null)||
                            ($relation->type==null)
                            )
                                class="table-warning"
                        @endif


                          >
                            <td>

                            </td>
                            <td>
                                {{ $relation->name ?? '' }}
                            </td>
                            <td>
                                {{ $relation->type ?? '' }}
                            </td>
                            <td>
                              @if ($relation->importance==1)
                                  {{ trans('cruds.relation.fields.importance_level.low') }}
                              @elseif ($relation->importance==2)
                                  {{ trans('cruds.relation.fields.importance_level.medium') }}
                              @elseif ($relation->importance==3)
                                  {{ trans('cruds.relation.fields.importance_level.high') }}
                              @elseif ($relation->importance==4)
                                  {{ trans('cruds.relation.fields.importance_level.critical') }}
                              @endif
                            </td>
                            <td>
                                {{ $relation->source->name ?? '' }}
                            </td>
                            <td>
                                {{ $relation->destination->name ?? '' }}
                            </td>
                            <td>
                                @can('relation_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.relations.show', $relation->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('relation_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.relations.edit', $relation->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('relation_delete')
                                    <form action="{{ route('admin.relations.destroy', $relation->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('relation_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.relations.massDestroy') }}",
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
  let table = $('.datatable-Relation:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
