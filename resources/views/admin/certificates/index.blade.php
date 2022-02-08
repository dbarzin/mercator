@extends('layouts.admin')
@section('content')
@can('certificate_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.certificates.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.certificate.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.certificate.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Entity">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.certificate.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.certificate.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.certificate.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.certificate.fields.start_validity') }}
                        </th>
                        <th>
                            {{ trans('cruds.certificate.fields.end_validity') }}
                        </th>
                        <th>
                            {{ trans('cruds.certificate.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($certificates as $certificate)
                        <tr data-entry-id="{{ $certificate->id }}"
                        @if(($certificate->description==null)||
                            ($certificate->type==null)||
                            ($certificate->start_validity==null)||
                            ($certificate->end_validity==null)||
                            (
                            ($certificate->logical_servers->count()==0)&&($certificate->applications->count()==0)
                            ))
                                class="table-warning"
                        @endif
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.certificates.show', $certificate->id) }}">
                                {{ $certificate->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $certificate->type ?? '' !!}
                            </td>
                            <td>
                                {!! $certificate->description ?? '' !!}
                            </td>
                            <td>
                                @if($certificate->start_validity!=null)
                                    {!! Carbon\Carbon::createFromFormat('d/m/Y', $certificate->start_validity)->format('Y-m-d')  ?? '' !!}
                                @endif
                            </td>
                            <td>
                                @if($certificate->end_validity!=null)
                                    {!! Carbon\Carbon::createFromFormat('d/m/Y', $certificate->end_validity)->format('Y-m-d')  ?? '' !!}
                                @endif                                
                            </td>
                            <td>
                                @if (($certificate->status==null) || ($certificate->status==0))
                                    {{ trans('cruds.certificate.fields.status_good') }}
                                @elseif ($certificate->status==1)
                                    {{ trans('cruds.certificate.fields.status_revoked') }}
                                @elseif ($certificate->status==2)
                                    {{ trans('cruds.certificate.fields.status_unknown') }}
                                @endif
                            </td>                            
                            <td>
                                @can('certificate_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.certificates.show', $certificate->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('certificate_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.certificates.edit', $certificate->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('certificate_delete')
                                    <form action="{{ route('admin.certificates.destroy', $certificate->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('certificate_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.certificates.massDestroy') }}",
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
