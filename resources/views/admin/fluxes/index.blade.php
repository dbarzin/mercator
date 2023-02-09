@extends('layouts.admin')
@section('content')
@can('flux_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.fluxes.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.flux.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.flux.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Flux">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.flux.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.flux.fields.nature_short') }}
                        </th>
                        <th>
            			    {{ trans('cruds.flux.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.flux.fields.source') }}
                        </th>
                        <th>
                            {{ trans('cruds.flux.fields.destination') }}
                        </th>
                        <th>
                            {{ trans('cruds.flux.fields.crypted') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fluxes as $key => $flux)
                        <tr data-entry-id="{{ $flux->id }}"

@if(
    // no description
    ($flux->description==null)||
    // no source
    (
      ($flux->application_source==null)&&
      ($flux->service_source==null)&&
      ($flux->module_source==null)&&
      ($flux->database_source==null)
    )||
    // no destination
    (
      ($flux->application_dest==null)&&
      ($flux->service_dest==null)&&
      ($flux->module_dest==null)&&
      ($flux->database_dest==null)
    )
  )
                          class="table-warning"
@endif


                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.fluxes.show', $flux->id) }}">
                                {{ $flux->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $flux->nature }}
                            </td>
                            <td>
                                {!! $flux->description ?? '' !!}
                            </td>
                            <td>
                              @if ($flux->application_source!=null)
                              <a href="{{ route('admin.applications.show', $flux->application_source_id) }}">
                              {{ $flux->application_source->name }}
                              @endif
                              @if ($flux->service_source!=null)
                              <a href="{{ route('admin.application-services.show', $flux->service_source_id) }}">
                              {{ $flux->service_source->name }}
                              </a>
                              @endif
                              @if ($flux->module_source!=null)
                              <a href="{{ route('admin.application-modules.show', $flux->module_source_id) }}">
                              {{ $flux->module_source->name }}
                              </a>
                              @endif
                              @if ($flux->database_source!=null)
                              <a href="{{ route('admin.databases.show', $flux->database_source_id) }}">
                              {{ $flux->database_source->name }}
                              </a>
                              @endif                              
                            </td>
                            <td>
                              @if ($flux->application_dest!=null)
                              <a href="{{ route('admin.applications.show', $flux->application_dest_id) }}">
                              {{ $flux->application_dest->name }}
                              @endif
                              @if ($flux->service_dest!=null)
                              <a href="{{ route('admin.application-services.show', $flux->service_dest_id) }}">
                              {{ $flux->service_dest->name }}
                              </a>
                              @endif
                              @if ($flux->module_dest!=null)
                              <a href="{{ route('admin.application-modules.show', $flux->module_dest_id) }}">
                              {{ $flux->module_dest->name }}
                              </a>
                              @endif
                              @if ($flux->database_dest!=null)
                              <a href="{{ route('admin.databases.show', $flux->database_dest_id) }}">
                              {{ $flux->database_dest->name }}
                              </a>
                              @endif                              
                            </td>
                            <td>
                              @if ($flux->crypted==0)
                                  Non
                              @elseif ($flux->crypted==1)
                                  Oui
                              @endif
                            </td>
                            <td>
                                @can('flux_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.fluxes.show', $flux->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('flux_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.fluxes.edit', $flux->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('flux_delete')
                                    <form action="{{ route('admin.fluxes.destroy', $flux->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('flux_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.fluxes.massDestroy') }}",
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
  let table = $('.datatable-Flux:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection
