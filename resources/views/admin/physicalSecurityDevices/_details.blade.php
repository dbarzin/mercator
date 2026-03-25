@props([
    'physicalSecurityDevice',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $physicalSecurityDevice->getUID() }}">
    <tbody>
    <tr>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.name') }}
        </th>
        <td width="20%">
        @if($withLink)
        <a href="{{ route('admin.physical-security-devices.show', $physicalSecurityDevice->id) }}">{{ $physicalSecurityDevice->name }}</a>
        @else
            {{ $physicalSecurityDevice->name }}
        @endif
        </td>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.type') }}
        </th>
        <td width="20%">
            {{ $physicalSecurityDevice->type }}
        </td>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.attributes') }}
        </th>
        <td width="20%">
            @foreach(explode(" ",$physicalSecurityDevice->attributes) as $attribute)
                <span class="badge badge-info">{{ $attribute }}</span>
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.physicalSecurityDevice.fields.description') }}
        </th>
        <td colspan="4">
            {!! $physicalSecurityDevice->description !!}
        </td>
        <td width="10%">
            @if ($physicalSecurityDevice->icon_id === null)
                <img src='/images/securitydevice.png' width='60' height='60'>
            @else
                <img src='{{ route('admin.documents.show', $physicalSecurityDevice->icon_id) }}'
                     width='60'
                     height='60'>
            @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.physicalSecurityDevice.fields.address_ip') }}
        </th>
        <td colspan="5">
            {{ $physicalSecurityDevice->address_ip }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.physicalSecurityDevice.fields.security_devices') }}
        </th>
        <td colspan="5">
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
        <td>
            @if($physicalSecurityDevice->site!=null)
                <a href="{{ route('admin.sites.show', $physicalSecurityDevice->site->id) }}">
                    {{ $physicalSecurityDevice->site->name ?? '' }}
                </a>
            @endif
        </td>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.building') }}
        </th>
        <td>
            @if($physicalSecurityDevice->building!=null)
                <a href="{{ route('admin.buildings.show', $physicalSecurityDevice->building->id) }}">
                    {{ $physicalSecurityDevice->building->name ?? '' }}
                </a>
            @endif
        </td>
        <th width='10%'>
            {{ trans('cruds.physicalSecurityDevice.fields.bay') }}
        </th>
        <td>
            @if($physicalSecurityDevice->bay!=null)
                <a href="{{ route('admin.bays.show', $physicalSecurityDevice->bay->id) }}">
                    {{ $physicalSecurityDevice->bay->name ?? '' }}
                </a>
            @endif
        </td>
    </tr>
    </tbody>
</table>
