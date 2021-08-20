@extends('layouts.admin')
@section('content')
@can('information_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.information.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.information.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.information.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Information">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.information.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.information.fields.descrition') }}
                        </th>
                        <th>
                            {{ trans('cruds.information.fields.owner') }}
                        </th>
                        <th>
                            {{ trans('cruds.information.fields.sensitivity') }}
                        </th>
                        <th>
                            {{ trans('cruds.information.fields.security_need') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($information as $key => $information)
                        <tr data-entry-id="{{ $information->id }}"
                            @if(($information->descrition==null)||
                                ($information->owner==null)||
                                ($information->administrator==null)||
                                ($information->storage==null)||                                
                                ((auth()->user()->granularity>=2)&&                                
                                    (
                                    ($information->security_need_c==null)||
                                    ($information->security_need_i==null)||
                                    ($information->security_need_a==null)||
                                    ($information->security_need_t==null)
                                    )
                                )||                                
                                ($information->sensitivity==null)
                                )
                                                      class="table-warning"
                            @endif
                            >
                            <td>

                            </td>
                            <td>
                                {{ $information->name ?? '' }}
                            </td>
                            <td>
                                {!! $information->descrition ?? '' !!}
                            </td>
                            <td>
                                {!! $information->owner ?? '' !!}
                            </td>
                            <td>
                                {{ $information->sensitivity ?? '' }}
                            </td>
                            <td>                                
                                @if ($information->security_need_c==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($information->security_need_c==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($information->security_need_c==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($information->security_need_c==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif                                    
                                -
                                @if ($information->security_need_i==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($information->security_need_i==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($information->security_need_i==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($information->security_need_i==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif                                    
                                -
                                @if ($information->security_need_a==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($information->security_need_a==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($information->security_need_a==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($information->security_need_a==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif                                    
                                -
                                @if ($information->security_need_t==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($information->security_need_t==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($information->security_need_t==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($information->security_need_t==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif                                    

                            </td>
                            <td>
                                @can('information_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.information.show', $information->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('information_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.information.edit', $information->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('information_delete')
                                    <form action="{{ route('admin.information.destroy', $information->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('information_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.information.massDestroy') }}",
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
  let table = $('.datatable-Information:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection