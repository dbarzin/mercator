@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.bays.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$bay->getUID()}}">
            {{ trans('global.explore') }}
        </a>

        @can('bay_edit')
            <a class="btn btn-info" href="{{ route('admin.bays.edit', $bay->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('bay_create')
            <a class="btn btn-warning" href="{{ route('admin.bays.clone', $bay->id) }}">
                {{ trans('global.clone') }}
            </a>
        @endcan

        @can('bay_delete')
            <form action="{{ route('admin.bays.destroy', $bay->id) }}" method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.bay.title') }}
        </div>
        <div class="card-body">
        @include('admin.bays._details', [
            'bay' => $bay,
            'withLink' => false,
        ])
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $bay->created_at ? $bay->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $bay->updated_at ? $bay->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>

    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.bays.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
