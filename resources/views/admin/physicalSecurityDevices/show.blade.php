@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalSecurityDevice.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-security-devices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @can('physical_security_device_edit')
                    <a class="btn btn-info" href="{{ route('admin.physical-security-devices.edit', $physicalSecurityDevice->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('physical_security_device_delete')
                    <form action="{{ route('admin.physical-security-devices.destroy', $physicalSecurityDevice->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.physicalSecurityDevice.fields.name') }}
                        </th>
                        <td>
                            {{ $physicalSecurityDevice->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.type') }}
                        </th>
                        <td>
                            {{ $physicalSecurityDevice->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.description') }}
                        </th>
                        <td>
                            {!! $physicalSecurityDevice->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.site') }}
                        </th>
                        <td>
                            {{ $physicalSecurityDevice->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.building') }}
                        </th>
                        <td>
                            {{ $physicalSecurityDevice->building->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSecurityDevice.fields.bay') }}
                        </th>
                        <td>
                            {{ $physicalSecurityDevice->bay->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-security-devices.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection