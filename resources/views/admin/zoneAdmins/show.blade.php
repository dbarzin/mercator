@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.zone-admins.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$zoneAdmin->getUID()}}">
        {{ trans('global.explore') }}
    </a>

    @can('zone_admin_edit')
        <a class="btn btn-info" href="{{ route('admin.zone-admins.edit', $zoneAdmin->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('zone_admin_edit')
        <form action="{{ route('admin.zone-admins.destroy', $zoneAdmin->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.zoneAdmin.title') }}
    </div>
    <div class="card-body">
        @include('admin.zoneAdmins._details', [
            'zoneAdmin' => $zoneAdmin,
            'withLink' => false,
        ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $zoneAdmin->created_at ? $zoneAdmin->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $zoneAdmin->updated_at ? $zoneAdmin->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.zone-admins.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
