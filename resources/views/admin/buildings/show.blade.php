@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.buildings.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=BUILDING_{{$building->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('building_edit')
        <a class="btn btn-info" href="{{ route('admin.buildings.edit', $building->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('building_delete')
        <form action="{{ route('admin.buildings.destroy', $building->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.building.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.building.fields.name') }}
                    </th>
                    <td>
                        {{ $building->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.building.fields.description') }}
                    </th>
                    <td>
                        {!! $building->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.building.fields.camera') }}
                    </th>
                    <td>
                        {{ $building->camera ? trans('global.yes') : trans('global.no') }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.building.fields.badge') }}
                    </th>
                    <td>
                        {{ $building->badge ? trans('global.yes') : trans('global.no') }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.building.fields.site') }}
                    </th>
                    <td>
                        @if ($building->site!=null)
                            <a href="{{ route('admin.sites.show', $building->site->id) }}">
                            {{ $building->site->name ?? '' }}
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.building.fields.bays') }}
                    </th>
                    <td>
                        @foreach($building->roomBays as $bay)
                            <a href="{{ route('admin.bays.show', $bay->id) }}">
                            {{ $bay->name ?? '' }}
                            </a>
                            @if ($building->roomBays->last()!=$bay)
                            ,
                            @endif
                        @endforeach
                    </td>
                </tr>

            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $building->created_at ? $building->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $building->updated_at ? $building->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.buildings.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

@endsection
