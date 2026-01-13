@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.processes.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=PROCESS_{{$process->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('process_edit')
        <a class="btn btn-info" href="{{ route('admin.processes.edit', $process->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('process_delete')
        <form action="{{ route('admin.processes.destroy', $process->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan

</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.process.title') }}
    </div>

    <div class="card-body">

        @include('admin.processes._details', [
            'process' => $process,
            'withLink' => false,
        ])

    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $process->created_at ? $process->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $process->updated_at ? $process->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>

<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.processes.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
