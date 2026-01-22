@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.vlans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=VLAN_{{$vlan->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('vlan_edit')
        <a class="btn btn-info" href="{{ route('admin.vlans.edit', $vlan->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('vlan_create')
        <a class="btn btn-warning" href="{{ route('admin.vlans.clone', $vlan->id) }}">
            {{ trans('global.clone') }}
        </a>
    @endcan

    @can('vlan_delete')
        <form action="{{ route('admin.vlans.destroy', $vlan->id) }}" method="POST"
              onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.vlan.title') }}
    </div>

    <div class="card-body">
    @include('admin.vlans._details', [
        'vlan' => $vlan,
        'withLink' => false,
    ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $vlan->created_at ? $vlan->created_at->format(trans('global.timestamp')) : '' }}
        |
        {{ trans('global.updated_at') }} {{ $vlan->updated_at ? $vlan->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.vlans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
