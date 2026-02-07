@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.macro-processuses.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=MACROPROCESS_{{$macroProcessus->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('macro_processus_edit')
        <a class="btn btn-info" href="{{ route('admin.macro-processuses.edit', $macroProcessus->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('macro_processus_delete')
        <form action="{{ route('admin.macro-processuses.destroy', $macroProcessus->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.macroProcessus.title') }}
    </div>
    <div class="card-body">
        @include('admin.macroProcessuses._details', [
            'macroProcessus' => $macroProcessus,
            'withLink' => false,
        ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $macroProcessus->created_at ? $macroProcessus->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $macroProcessus->updated_at ? $macroProcessus->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>

<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.macro-processuses.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

@endsection
