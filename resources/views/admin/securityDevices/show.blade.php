@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.security-devices.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=LSECURITY_{{$securityDevice->id}}">
            {{ trans('global.explore') }}
        </a>

        @can('entity_edit')
            <a class="btn btn-info" href="{{ route('admin.security-devices.edit', $securityDevice->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('entity_delete')
            <form action="{{ route('admin.security-devices.destroy', $securityDevice->id) }}" method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.securityDevice.title') }}
        </div>

        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.securityDevice.fields.name') }}
                    </th>
                    <td colspan="2">
                        {{ $securityDevice->name }}
                    </td>
                </tr>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.securityDevice.fields.type') }}
                    </th>
                    <td colspan="2">
                        {{ $securityDevice->type }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.securityDevice.fields.attributes') }}
                    </th>
                    <td colspan="2">
                        @foreach(explode(" ",$securityDevice->attributes) as $attribute)
                            <span class="badge badge-info">{{ $attribute }}</span>
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.securityDevice.fields.description') }}
                    </th>
                    <td>
                        {!! $securityDevice->description !!}
                    </td>
                    <td width="10%">
                        @if ($securityDevice->icon_id === null)
                            <img src='/images/securitydevice.png' width='120' height='120'>
                        @else
                            <img src='{{ route('admin.documents.show', $securityDevice->icon_id) }}' width='120'
                                 height='120'>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.securityDevice.fields.address_ip') }}
                    </th>
                    <td>
                        {{ $securityDevice->address_ip}}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.securityDevice.fields.applications') }}
                    </th>
                    <td>
                        @foreach($securityDevice->applications as $application)
                            <a href="{{ route('admin.applications.show', $application->id) }}">{{ $application->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.securityDevice.fields.physical_security_devices') }}
                    </th>
                    <td>
                        @foreach($securityDevice->physicalSecurityDevices as $device)
                            <a href="{{ route('admin.physical-security-devices.show', $device->id) }}">{{ $device->name }}</a>
                            @if(!$loop->last)
                                ,
                            @endif
                        @endforeach
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $securityDevice->created_at ? $securityDevice->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $securityDevice->updated_at ? $securityDevice->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.security-devices.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
