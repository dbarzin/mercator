@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.data-processings.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        @can('data_processing_edit')
            <a class="btn btn-info" href="{{ route('admin.data-processings.edit', $dataProcessing->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('data_processing_delete')
            <form action="{{ route('admin.data-processings.destroy', $dataProcessing->id) }}" method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan
    </div>
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.dataProcessing.title') }}
        </div>
        <div class="card-body">
            @include('admin.dataProcessing._details', [
                'dataProcessing' => $dataProcessing,
                'withLink' => false,
            ])
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $dataProcessing->created_at ? $dataProcessing->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $dataProcessing->updated_at ? $dataProcessing->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.data-processings.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
