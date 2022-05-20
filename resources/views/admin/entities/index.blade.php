@extends('layouts.admin')
@section('content')
@can('entity_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.entities.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.entity.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.entity.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Entity">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.is_external') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.contact_point') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.security_level') }}
                        </th>
                        <th>
                            {{ trans('cruds.entity.fields.exploits') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($entities as $key => $entity)
                        <tr data-entry-id="{{ $entity->id }}"
                            @if(($entity->description==null)||
                                ($entity->is_external==null)||
                                ($entity->contact_point==null)||
                                ($entity->security_level==null)||
                                ($entity->contact_point==null)||
                                ($entity->entitiesProcesses->count()==0)
                                )
                                class="table-warning"
                            @endif
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.entities.show', $entity->id) }}">
                                {{ $entity->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $entity->description ?? '' !!}
                            </td>
                            <td>
                                'tata'{!!  $entity->is_external  == null ? '' : ($entity->is_external ? 'Oui' : 'Non')  !!}
                            </td>  
                            <td>
                                {!! $entity->contact_point  ?? '' !!}
                            </td>                        
                            <td>
                                {!! $entity->security_level ?? '' !!}
                            </td>
                            <td>
                                @foreach($entity->applications as $application)
                                    <a href="{{ route('admin.applications.show', $application->id) }}">
                                        {{ $application->name }}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                                @if(($entity->applications->count()>0)&&($entity->databases->count()>0))
                                    ,<br>
                                @endif
                                @foreach($entity->databases as $database)
                                    <a href="{{ route('admin.databases.show', $database->id) }}">
                                        {{ $database->name }}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @can('entity_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.entities.show', $entity->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('entity_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.entities.edit', $entity->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('entity_delete')
                                    <form action="{{ route('admin.entities.destroy', $entity->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('entity_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.entities.massDestroy') }}",
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
  let table = $('.datatable-Entity:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
})

</script>
@endsection