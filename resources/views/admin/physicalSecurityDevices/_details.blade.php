<table class="table table-bordered table-striped">
    <tbody>
    <tr>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.name') }}
        </th>
        <td colspan='2'>
            {{ $physicalSecurityDevice->name }}
        </td>
    </tr>
    <tr>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.type') }}
        </th>
        <td colspan='2'>
            {{ $physicalSecurityDevice->type }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.physicalSecurityDevice.fields.attributes') }}
        </th>
        <td colspan="2">
            @foreach(explode(" ",$physicalSecurityDevice->attributes) as $attribute)
                <span class="badge badge-info">{{ $attribute }}</span>
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.physicalSecurityDevice.fields.description') }}
        </th>
        <td>
            {!! $physicalSecurityDevice->description !!}
        </td>
        <td width="10%">
            @if ($physicalSecurityDevice->icon_id === null)
                <img src='/images/securitydevice.png' width='120' height='120'>
            @else
                <img src='{{ route('admin.documents.show', $physicalSecurityDevice->icon_id) }}'
                     width='120'
                     height='120'>
            @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.physicalSecurityDevice.fields.address_ip') }}
        </th>
        <td colspan="2">
            {{ $physicalSecurityDevice->address_ip }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.physicalSecurityDevice.fields.security_devices') }}
        </th>
        <td colspan="2">
            @foreach($physicalSecurityDevice->securityDevices as $device)
                <a href="{{ route('admin.security-devices.show', $device->id) }}">{{ $device->name }}</a>
                @if(!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.site') }}
        </th>
        <td colspan="2">
            @if($physicalSecurityDevice->site!=null)
                <a href="{{ route('admin.sites.show', $physicalSecurityDevice->site->id) }}">
                    {{ $physicalSecurityDevice->site->name ?? '' }}
                </a>
            @endif
        </td>
    </tr>
    <tr>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.building') }}
        </th>
        <td colspan="2">
            @if($physicalSecurityDevice->building!=null)
                <a href="{{ route('admin.buildings.show', $physicalSecurityDevice->building->id) }}">
                    {{ $physicalSecurityDevice->building->name ?? '' }}
                </a>
            @endif
        </td>
    </tr>
    <tr>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.bay') }}
        </th>
        <td colspan="2">
            @if($physicalSecurityDevice->bay!=null)
                <a href="{{ route('admin.bays.show', $physicalSecurityDevice->bay->id) }}">
                    {{ $physicalSecurityDevice->bay->name ?? '' }}
                </a>
            @endif
        </td>
    </tr>
    </tbody>
</table>
