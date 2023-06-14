@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalServer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=PSERVER_{{$physicalServer->id}}">
                    {{ trans('global.explore') }}
                </a>

                @can('physical_server_edit')
                    <a class="btn btn-info" href="{{ route('admin.physical-servers.edit', $physicalServer->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('physical_server_delete')
                    <form action="{{ route('admin.physical-servers.destroy', $physicalServer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.physicalServer.fields.name') }}
                        </th>
                        <td>
                            {{ $physicalServer->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.description') }}
                        </th>
                        <td>
                            {!! $physicalServer->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.configuration') }}
                        </th>
                        <td>
                            {!! $physicalServer->configuration !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.type') }}
                        </th>
                        <td>
                            {{ $physicalServer->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.address_ip') }}
                        </th>
                        <td>
                            {{ $physicalServer->address_ip }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.site') }}
                        </th>
                        <td>
                            @if ($physicalServer->site!=null)
                                <a href="{{ route('admin.sites.show', $physicalServer->site->id) }}">
                                {{ $physicalServer->site->name ?? '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.building') }}
                        </th>
                        <td>
                            @if ($physicalServer->building!=null)
                                <a href="{{ route('admin.buildings.show', $physicalServer->building->id) }}">
                                {{ $physicalServer->building->name ?? '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.bay') }}
                        </th>
                        <td>
                            @if ($physicalServer->bay!=null)
                                <a href="{{ route('admin.bays.show', $physicalServer->bay->id) }}">
                                {{ $physicalServer->bay->name ?? '' }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.physical_switch') }}                    
                        </th>
                        <td>
                            {{ $physicalServer->physicalSwitch->name ?? '' }}
                        </td>
                    </tr>


                    <tr>
                        <th>
                            {{ trans('cruds.physicalServer.fields.responsible') }}
                        </th>
                        <td>
                            {{ $physicalServer->responsible }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-servers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $physicalServer->created_at ? $physicalServer->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $physicalServer->updated_at ? $physicalServer->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>
@endsection