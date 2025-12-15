@extends('layouts.admin')
@section('content')
    @can('domaine_ad_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route("admin.domaine-ads.create") }}">
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
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.domaineAd.title') }}
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
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($domaineAds as $domaineAd)
                        <tr data-entry-id="{{ $domaineAd->id }}"
                            @if (
                                ($domaineAd->description===null)||
                                ($domaineAd->domain_ctrl_cnt===null)||
                                ($domaineAd->user_count===null)||
                                ($domaineAd->machine_count===null)||
                                ($domaineAd->relation_inter_domaine===null)
                                )
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.domaine-ads.show', $domaineAd->id) }}">
                                    {{ $domaineAd->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                @foreach($domaineAd->forestAds as $forestAd)
                                    <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                                        {{ $forestAd->name }}
                                    </a>{{ !$loop->last ? ',' : '' }}
                                @endforeach
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
                            <td nowrap>
                                @can('domaine_ad_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.domaine-ads.show', $domaineAd->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('domaine_ad_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.domaine-ads.edit', $domaineAd->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('domaine_ad_delete')
                                    <form action="{{ route('admin.domaine-ads.destroy', $domaineAd->id) }}"
                                          method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
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
            'title' => trans("cruds.domaineAd.title_singular"),
            'URL' => route('admin.domaine-ads.massDestroy'),
            'canDelete' => auth()->user()->can('domaine_ad_delete') ? true : false
        ));
    </script>
@endsection
