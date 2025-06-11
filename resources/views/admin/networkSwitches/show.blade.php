@extends('layouts.admin')
@section('content')

<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.network-switches.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=SWITCH_{{$networkSwitch->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('entity_edit')
        <a class="btn btn-info" href="{{ route('admin.network-switches.edit', $networkSwitch->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('entity_delete')
        <form action="{{ route('admin.network-switches.destroy', $networkSwitch->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan

</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.networkSwitch.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.networkSwitch.fields.name') }}
                    </th>
                    <td>
                        {{ $networkSwitch->name }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.networkSwitch.fields.description') }}
                    </th>
                    <td>
                        {!! $networkSwitch->description !!}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.networkSwitch.fields.ip') }}
                    </th>
                    <td>
                        {{ $networkSwitch->ip }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.networkSwitch.fields.physical_switches') }}
                    </th>
                    <td>
                        @foreach($networkSwitch->physicalSwitches as $physicalSwitch)
                            <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">
                            {{ $physicalSwitch->name }}
                            </a>
                            @if (!$loop->last)
                            ,
                            @endif
                        @endforeach
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $networkSwitch->created_at ? $networkSwitch->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $networkSwitch->updated_at ? $networkSwitch->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.network-switches.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
