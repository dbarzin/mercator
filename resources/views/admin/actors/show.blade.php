@extends('layouts.admin')
@section('content')
<div class="form-group">
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.actors.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$actor->getUID()}}">
            {{ trans('global.explore') }}
        </a>

        @can('actor_edit')
            <a class="btn btn-info" href="{{ route('admin.actors.edit', $actor->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('entity_delete')
            <form action="{{ route('admin.actors.destroy', $actor->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan

    </div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.actor.title') }}
    </div>

    <div class="card-body">

    @include('admin.actors._details', [
        'actor' => $actor,
        'withLink' => false,
    ])

    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $actor->created_at ? $actor->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $actor->updated_at ? $actor->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.actors.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>



@endsection
