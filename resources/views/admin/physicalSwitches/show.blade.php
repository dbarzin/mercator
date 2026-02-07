@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.physical-switches.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=SWITCH_{{$physicalSwitch->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('physical_switch_edit')
        <a class="btn btn-info" href="{{ route('admin.physical-switches.edit', $physicalSwitch->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('physical_switch_create')
        <a class="btn btn-warning" href="{{ route('admin.physical-switches.clone', $physicalSwitch->id) }}">
            {{ trans('global.clone') }}
        </a>
    @endcan

    @can('physical_switch_delete')
        <form action="{{ route('admin.physical-switches.destroy', $physicalSwitch->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalSwitch.title') }}
    </div>
    <div class="card-body">
         @include('admin.physicalSwitches._details', [
             'physicalSwitch' => $physicalSwitch,
             'withLink' => false,
         ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $physicalSwitch->created_at ? $physicalSwitch->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $physicalSwitch->updated_at ? $physicalSwitch->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.physical-switches.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
