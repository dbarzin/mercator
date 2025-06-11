@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.forest-ads.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=FOREST_{{$forestAd->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('entity_edit')
        <a class="btn btn-info" href="{{ route('admin.forest-ads.edit', $forestAd->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('entity_delete')
        <form action="{{ route('admin.forest-ads.destroy', $forestAd->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.forestAd.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width='10%'>
                        {{ trans('cruds.forestAd.fields.name') }}
                    </th>
                    <td>
                        {{ $forestAd->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.forestAd.fields.description') }}
                    </th>
                    <td>
                        {!! $forestAd->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.forestAd.fields.zone_admin') }}
                    </th>
                    <td>
                        @if ($forestAd->zone_admin_id!=null)
                        <a href="{{ route('admin.zone-admins.show', $forestAd->zone_admin->id) }}">
                        {{ $forestAd->zone_admin->name ?? '' }}
                        </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.forestAd.fields.domaines') }}
                    </th>
                    <td>
                        @foreach($forestAd->domaines as $domaine)
                        <a href="{{ route('admin.domaine-ads.show', $domaine->id) }}">
                        {{ $domaine->name }}
                        </a>
                        @if ($forestAd->domaines->last()!=$domaine)
                        ,
                        @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $forestAd->created_at ? $forestAd->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $forestAd->updated_at ? $forestAd->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.forest-ads.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
