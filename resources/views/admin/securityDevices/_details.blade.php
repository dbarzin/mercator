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
