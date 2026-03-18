@props([
    'securityDevice',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $securityDevice->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.securityDevice.fields.name') }}
            </th>
            <td width="20%">
            @if($withLink)
            <a href="{{ route('admin.security-devices.show', $securityDevice->id) }}">{{ $securityDevice->name }}</a>
            @else
            {{ $securityDevice->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.securityDevice.fields.type') }}
            </th>
            <td width="20%">
                {{ $securityDevice->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.securityDevice.fields.attributes') }}
            </th>
            <td width="20%">
                @foreach(explode(" ",$securityDevice->attributes) as $attribute)
                    <span class="badge badge-info">{{ $attribute }}</span>
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.securityDevice.fields.description') }}
            </th>
            <td colspan="4">
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
            <td colspan="5">
                {{ $securityDevice->address_ip}}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.securityDevice.fields.applications') }}
            </th>
            <td colspan="5">
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
            <td colspan="5">
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
