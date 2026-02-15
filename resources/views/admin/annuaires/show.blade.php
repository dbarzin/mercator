@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.annuaires.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$annuaire->getUID()}}">
        {{ trans('global.explore') }}
    </a>

    @can('annuaire_edit')
        <a class="btn btn-info" href="{{ route('admin.annuaires.edit', $annuaire->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('annuaire_edit')
        <form action="{{ route('admin.annuaires.destroy', $annuaire->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.annuaire.title') }}
    </div>
    <div class="card-body">
    @include('admin.annuaires._details', [
        'annuaire' => $annuaire,
        'withLink' => false,
    ])
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $annuaire->created_at ? $annuaire->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $annuaire->updated_at ? $annuaire->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.annuaires.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
