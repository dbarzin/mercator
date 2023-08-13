@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalSwitch.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-switches.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=SWITCH_{{$physicalSwitch->id}}">
                    {{ trans('global.explore') }}
                </a>

                @can('physical_switch_edit')
                    <a class="btn btn-info" href="{{ route('admin.physical-switches.edit', $physicalSwitch->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('physical_switch_delete')
                    <form action="{{ route('admin.physical-switches.destroy', $physicalSwitch->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.physicalSwitch.fields.name') }}
                        </th>
                        <td>
                            {{ $physicalSwitch->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.description') }}
                        </th>
                        <td>
                            {!! $physicalSwitch->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.type') }}
                        </th>
                        <td>
                            {{ $physicalSwitch->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.site') }}
                        </th>
                        <td>
                            @if ($physicalSwitch->site!=null)
                                <a href="{{ route('admin.sites.show', $physicalSwitch->site->id) }}">
                                {{ $physicalSwitch->site->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.building') }}
                        </th>
                        <td>
                            @if ($physicalSwitch->building!=null)
                                <a href="{{ route('admin.buildings.show', $physicalSwitch->building->id) }}">
                                {{ $physicalSwitch->building->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.bay') }}
                        </th>
                        <td>
                            @if ($physicalSwitch->bay!=null)
                                <a href="{{ route('admin.bays.show', $physicalSwitch->bay->id) }}">
                                {{ $physicalSwitch->bay->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-switches.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $physicalSwitch->created_at ? $physicalSwitch->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $physicalSwitch->updated_at ? $physicalSwitch->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>
@endsection