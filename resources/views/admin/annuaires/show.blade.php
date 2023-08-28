@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.annuaire.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.annuaires.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=ANNUAIRE_{{$annuaire->id}}">
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
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.annuaire.fields.name') }}
                        </th>
                        <td>
                            {{ $annuaire->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.annuaire.fields.description') }}
                        </th>
                        <td>
                            {!! $annuaire->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.annuaire.fields.solution') }}
                        </th>
                        <td>
                            {{ $annuaire->solution }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.annuaire.fields.zone_admin') }}
                        </th>
                        <td>
                            @if ($annuaire->zone_admin!=null)
                            <a href="{{ route('admin.zone-admins.show', $annuaire->zone_admin->id) }}">
                            {{ $annuaire->zone_admin->name ?? '' }}
                            @endif
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.annuaires.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $annuaire->created_at ? $annuaire->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $annuaire->updated_at ? $annuaire->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>
@endsection