@can('domaine_ad_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.domaine-ads.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.domaineAd.title_singular') }}
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        {{ trans('cruds.domaineAd.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-forestAdDomaineAds">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.domain_ctrl_cnt') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.user_count') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.machine_count') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.relation_inter_domaine') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.zone_admin') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.forest_ad') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($domaineAds as $key => $domaineAd)
                        <tr data-entry-id="{{ $domaineAd->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $domaineAd->id ?? '' }}
                            </td>
                            <td>
                                {{ $domaineAd->name ?? '' }}
                            </td>
                            <td>
                                {{ $domaineAd->domain_ctrl_cnt ?? '' }}
                            </td>
                            <td>
                                {{ $domaineAd->user_count ?? '' }}
                            </td>
                            <td>
                                {{ $domaineAd->machine_count ?? '' }}
                            </td>
                            <td>
                                {{ $domaineAd->relation_inter_domaine ?? '' }}
                            </td>
                            <td>
                                {{ $domaineAd->zone_admin->name ?? '' }}
                            </td>
                            <td>
                                {{ $domaineAd->forest_ad->name ?? '' }}
                            </td>
                            <td>
                                @can('domaine_ad_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.domaine-ads.show', $domaineAd->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('domaine_ad_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.domaine-ads.edit', $domaineAd->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('domaine_ad_delete')
                                    <form action="{{ route('admin.domaine-ads.destroy', $domaineAd->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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

@section('scripts')
@parent
<script>
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('domaine_ad_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.domaine-ads.massDestroy') }}",
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
    pageLength: 100,
  });
  $('.datatable-forestAdDomaineAds:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection