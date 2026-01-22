@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.wans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
    @can('wan_edit')
        <a class="btn btn-info" href="{{ route('admin.wans.edit', $wan->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('wan_delete')
        <form action="{{ route('admin.wans.destroy', $wan->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.wan.title') }}
    </div>
    <div class="card-body">
    @include('admin.wans._details', [
        'wan' => $wan,
        'withLink' => false,
    ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $wan->created_at ? $wan->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $wan->updated_at ? $wan->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.wans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
