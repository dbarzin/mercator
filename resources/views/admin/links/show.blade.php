@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.links.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$link->sourceId()}},{{$link->destinationId()}}">
        {{ trans('global.explore') }}
    </a>

    @can('physical_link_edit')
        <a class="btn btn-info" href="{{ route('admin.links.edit', $link->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('physical_link_delete')
        <form action="{{ route('admin.links.destroy', $link->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalLink.title') }}
    </div>
    <div class="card-body">
        @include('admin.links._details', [
            'link' => $link,
            'withLink' => false,
        ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $link->created_at ? $link->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $link->updated_at ? $link->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.links.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
