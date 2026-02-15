@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.mans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$man->getUID()}}">
        {{ trans('global.explore') }}
    </a>

    @can('man_edit')
        <a class="btn btn-info" href="{{ route('admin.mans.edit', $man->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('man_delete')
        <form action="{{ route('admin.mans.destroy', $man->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan

</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.man.title') }}
    </div>

    <div class="card-body">
        @include('admin.mans._details', [
            'man' => $man,
            'withLink' => false,
        ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $man->created_at ? $man->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $man->updated_at ? $man->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.mans.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
