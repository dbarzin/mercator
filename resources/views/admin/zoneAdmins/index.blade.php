@extends('layouts.admin')
@section('content')
@can('zone_admin_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.zone-admins.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.zoneAdmin.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.zoneAdmin.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.zoneAdmin.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.zoneAdmin.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.zoneAdmin.fields.annuaires') }}
                        </th>
                        <th>
                            {{ trans('cruds.zoneAdmin.fields.forests') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($zoneAdmins as $key => $zoneAdmin)
                        <tr data-entry-id="{{ $zoneAdmin->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.zone-admins.show', $zoneAdmin->id) }}">
                                    {{ $zoneAdmin->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $zoneAdmin->description ?? '' !!}
                            </td>
                            <td>
                                @foreach($zoneAdmin->zoneAdminAnnuaires as $annuaire)
                                <a href="{{ route('admin.annuaires.show', $annuaire->id) }}">
                                    {{ $annuaire->name }}
                                </a>
                                @if ($zoneAdmin->zoneAdminAnnuaires->last()!=$annuaire)
                                    ,
                                @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach($zoneAdmin->zoneAdminforestAds as $forestAd)
                                <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                                    {{ $forestAd->name ?? '' }}
                                </a>
                                @if ($zoneAdmin->zoneAdminforestAds->last()!=$forestAd)
                                    ,
                                @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('zone_admin_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.zone-admins.show', $zoneAdmin->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('zone_admin_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.zone-admins.edit', $zoneAdmin->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('zone_admin_delete')
                                    <form action="{{ route('admin.zone-admins.destroy', $zoneAdmin->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.zoneAdmin.title_singular"),
    'URL' => route('admin.zone-admins.massDestroy'),
    'canDelete' => auth()->user()->can('zone_admin_delete') ? true : false
));
</script>
@endsection
