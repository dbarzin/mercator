@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.bay.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bays.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=BAY_{{$bay->id}}">
                    {{ trans('global.explore') }}
                </a>

                @can('bay_edit')
                    <a class="btn btn-info" href="{{ route('admin.bays.edit', $bay->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('bay_delete')
                    <form action="{{ route('admin.bays.destroy', $bay->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.bay.fields.name') }}
                        </th>
                        <td>
                            {{ $bay->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bay.fields.description') }}
                        </th>
                        <td>
                            {!! $bay->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bay.fields.room') }}
                        </th>
                        <td>
                            @if ($bay->room!=null)
                                <a href="{{ route('admin.buildings.show', $bay->room->id) }}">
                                {{ $bay->room->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bays.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $bay->created_at ? $bay->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $bay->updated_at ? $bay->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>

@endsection