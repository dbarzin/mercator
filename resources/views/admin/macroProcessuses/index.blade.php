@extends('layouts.admin')
@section('content')
@can('macro_processus_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.macro-processuses.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.macroProcessus.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.macroProcessus.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-MacroProcessus">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.security_need') }}
                        </th>
                        <th>
                            {{ trans('cruds.macroProcessus.fields.owner') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($macroProcessuses as $key => $macroProcessus)
                        <tr data-entry-id="{{ $macroProcessus->id }}"
@if(($macroProcessus->description==null)||
    ($macroProcessus->io_elements==null)||
    ($macroProcessus->security_need==null)||
        (
        (auth()->user()->granularity>=2) && 
        ($macroProcessus->owner==null)
        )
    )
                          class="table-warning"
@endif
                            >
                            <td>

                            </td>
                            <td>
                                {{ $macroProcessus->name ?? '' }}
                            </td>
                            <td>
                                {!! $macroProcessus->description ?? '' !!}
                            </td>

                            <td>
                                @if ($macroProcessus->security_need==1) 
                                    Public
                                @elseif ($macroProcessus->security_need==2)
                                    Internal
                                @elseif ($macroProcessus->security_need==3)
                                    Confidential
                                @elseif ($macroProcessus->security_need==4)
                                    Secret
                                @endif
                            </td>
                            <td>
                                {{ $macroProcessus->owner }}
                            </td>

                            <td>
                                @can('macro_processus_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.macro-processuses.show', $macroProcessus->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('macro_processus_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.macro-processuses.edit', $macroProcessus->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('macro_processus_delete')
                                    <form action="{{ route('admin.macro-processuses.destroy', $macroProcessus->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('macro_processus_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.macro-processuses.massDestroy') }}",
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
  let table = $('.datatable-MacroProcessus:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection