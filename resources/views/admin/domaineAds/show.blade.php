@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.domaine-ads.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=DOMAIN_{{$domaineAd->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('entity_edit')
        <a class="btn btn-info" href="{{ route('admin.domaine-ads.edit', $domaineAd->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('entity_delete')
        <form action="{{ route('admin.domaine-ads.destroy', $domaineAd->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.domaineAd.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.domaineAd.fields.name') }}
                    </th>
                    <td>
                        {{ $domaineAd->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.domaineAd.fields.description') }}
                    </th>
                    <td>
                        {!! $domaineAd->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.domaineAd.fields.domain_ctrl_cnt') }}
                    </th>
                    <td>
                        {{ $domaineAd->domain_ctrl_cnt }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.domaineAd.fields.user_count') }}
                    </th>
                    <td>
                        {{ $domaineAd->user_count }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.domaineAd.fields.machine_count') }}
                    </th>
                    <td>
                        {{ $domaineAd->machine_count }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.domaineAd.fields.relation_inter_domaine') }}
                    </th>
                    <td>
                        {{ $domaineAd->relation_inter_domaine }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.forestAd.title') }}
                    </th>
                    <td>
                        @foreach($domaineAd->domainesForestAds as $forestAd)
                            <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                            {{ $forestAd->name }}
                            </a>
                        @if ($domaineAd->domainesForestAds->last()!=$forestAd)
                        ,
                        @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.logicalServer.title') }}
                    </th>
                    <td>
                        @foreach($domaineAd->logicalServers as $logicalServer)
                            <a href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                            {{ $logicalServer->name }}
                            </a>
                            @if ($loop->last!=$logicalServer)
                            ,
                            @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $domaineAd->created_at ? $domaineAd->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $domaineAd->updated_at ? $domaineAd->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.domaine-ads.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
