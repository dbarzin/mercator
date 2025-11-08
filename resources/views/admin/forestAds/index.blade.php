@extends('layouts.admin')
@section('content')
    @can('forest_ad_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route("admin.forest-ads.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.forestAd.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.forestAd.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.forestAd.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.forestAd.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.forestAd.fields.zone_admin') }}
                        </th>
                        <th>
                            {{ trans('cruds.forestAd.fields.domaines') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($forestAds as $key => $forestAd)
                        <tr data-entry-id="{{ $forestAd->id }}"
                            @if (
                                ($forestAd->description===null)||
                                ($forestAd->zone_admin_id===null)
                                )
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                                    {{ $forestAd->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $forestAd->description ?? '' !!}
                            </td>
                            <td>
                                @if ($forestAd->zone_admin!=null)
                                    <a href="{{ route('admin.zone-admins.show', $forestAd->zone_admin->id) }}">
                                        {{ $forestAd->zone_admin->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @foreach($forestAd->domaines as $domain)
                                    <a href="{{ route('admin.domaine-ads.show', $domain->id) }}">
                                        {{ $domain->name }}
                                    </a>
                                    @if ($forestAd->domaines->last()!=$domain)
                                        ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('forest_ad_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('forest_ad_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.forest-ads.edit', $forestAd->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('forest_ad_delete')
                                    <form action="{{ route('admin.forest-ads.destroy', $forestAd->id) }}" method="POST"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                          style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger"
                                               value="{{ trans('global.delete') }}">
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
            'title' => trans("cruds.forestAd.title_singular"),
            'URL' => route('admin.forest-ads.massDestroy'),
            'canDelete' => auth()->user()->can('forest_ad_delete') ? true : false
        ));
    </script>
@endsection
