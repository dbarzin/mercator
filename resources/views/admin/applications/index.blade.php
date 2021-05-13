@extends('layouts.admin')
@section('content')
@can('m_application_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.applications.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.application.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.application.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-MApplication">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.application.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.entity_resp') }}
                        </th>
                        <th>
                            {{ trans('cruds.application.fields.application_block') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($applications as $key => $application)
                        <tr data-entry-id="{{ $application->id }}"

                        @if (($application->description==null)||
                            ($application->responsible==null)||
                            ((auth()->user()->granularity>=2)&&($application->entity_resp_id==null))||
                            ((auth()->user()->granularity>=2)&&($application->entities->count()>0))||
                            ($application->technology==null)||
                            ($application->type==null)||
                            ((auth()->user()->granularity>=2)&&($application->users==null))||
                            ($application->security_need==null)||
                            ((auth()->user()->granularity>=2)&&($application->application_block==null))||
                            ($application->processes->count()==0)                            
                            )
                                class="table-warning"
                        @endif

                          >
                            <td>

                            </td>
                            <td>
                                {{ $application->name ?? '' }}
                            </td>
                            <td>
                                {!! $application->description ?? '' !!}
                            </td>
                            <td>
                                {{ $application->entity_resp->name ?? '' }}
                            </td>
                            <td>
                                {{ $application->application_block->name ?? '' }}
                            </td>
                            <td>
                                @can('m_application_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.applications.show', $application->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('m_application_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.applications.edit', $application->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('m_application_delete')
                                    <form action="{{ route('admin.applications.destroy', $application->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('m_application_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.applications.massDestroy') }}",
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
    "lengthMenu": [ 10, 50, 100, 500 ],
  });
  let table = $('.datatable-MApplication:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection