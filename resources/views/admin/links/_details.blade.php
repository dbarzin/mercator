<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="10%" valign="middle">
            {{ trans('cruds.physicalLink.fields.type') }}
            </th>
            <td valign="middle" width="20%">
            {{ $link->type }}
            </td>
            <td valign="middle">
                <div style="width: 40px; height: 40px; background-color: {{ $link->color }}; border: 1px solid #ccc; border-radius: 4px;"></div>
            </td>
        </tr>
        <tr>
            <th width="10%">
                {{ trans('cruds.physicalLink.fields.src') }}
            </th>
            <td colspan="2">
                @if ($link->peripheralSrc!=null)
                <a href="{{ route('admin.peripherals.show', $link->peripheral_src_id) }}">
                    {{ $link->peripheralSrc->name }}
                </a>
                @elseif ($link->phoneSrc!=null)
                <a href="{{ route('admin.phones.show', $link->phone_src_id) }}">
                    {{ $link->phoneSrc->name }}
                </a>
                @elseif ($link->physicalRouterSrc!=null)
                <a href="{{ route('admin.physical-routers.show', $link->physical_router_src_id) }}">
                    {{ $link->physicalRouterSrc->name }}
                </a>
                @elseif ($link->physicalSecurityDeviceSrc!=null)
                <a href="{{ route('admin.physical-security-devices.show', $link->physical_security_device_src_id) }}">
                    {{ $link->physicalSecurityDeviceSrc->name }}
                </a>
                @elseif ($link->physicalServerSrc!=null)
                <a href="{{ route('admin.physical-servers.show', $link->physical_server_src_id) }}">
                    {{ $link->physicalServerSrc->name }}
                </a>
                @elseif ($link->physicalSwitchSrc!=null)
                <a href="{{ route('admin.physical-switches.show', $link->physical_switch_src_id) }}">
                    {{ $link->physicalSwitchSrc->name }}
                </a>
                @elseif ($link->storageDeviceSrc!=null)
                <a href="{{ route('admin.storage-devices.show', $link->storage_device_src_id) }}">
                    {{ $link->storageDeviceSrc->name }}
                </a>
                @elseif ($link->wifiTerminalSrc!=null)
                <a href="{{ route('admin.wifi-terminals.show', $link->wifi_terminal_src_id) }}">
                    {{ $link->wifiTerminalSrc->name }}
                </a>
                @elseif ($link->workstationSrc!=null)
                <a href="{{ route('admin.workstations.show', $link->workstation_src_id) }}">
                    {{ $link->workstationSrc->name }}
                </a>
                @elseif ($link->routerSrc!=null)
                <a href="{{ route('admin.routers.show', $link->router_src_id) }}">
                    {{ $link->routerSrc->name }}
                </a>
                @endif
            </td>
        </tr>
        <tr>
            <th width="10%">
                {{ trans('cruds.physicalLink.fields.src_port') }}
            </th>
            <td colspan="2">
                {{ $link->src_port }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalLink.fields.dest') }}
            </th>
            <td colspan="2">
                @if ($link->peripheralDest!=null)
                <a href="{{ route('admin.peripherals.show', $link->peripheral_dest_id) }}">
                    {{ $link->peripheralDest->name }}
                </a>
                @elseif ($link->phoneDest!=null)
                <a href="{{ route('admin.phones.show', $link->phone_dest_id) }}">
                    {{ $link->phoneDest->name }}
                </a>
                @elseif ($link->physicalRouterDest!=null)
                <a href="{{ route('admin.physical-routers.show', $link->physical_router_dest_id) }}">
                    {{ $link->physicalRouterDest->name }}
                </a>
                @elseif ($link->physicalSecurityDeviceDest!=null)
                <a href="{{ route('admin.physical-security-devices.show', $link->physical_security_device_dest_id) }}">
                    {{ $link->physicalSecurityDeviceDest->name }}
                </a>
                @elseif ($link->physicalServerDest!=null)
                <a href="{{ route('admin.physical-servers.show', $link->physical_server_dest_id) }}">
                    {{ $link->physicalServerDest->name }}
                </a>
                @elseif ($link->physicalSwitchDest!=null)
                <a href="{{ route('admin.physical-switches.show', $link->physical_switch_dest_id) }}">
                    {{ $link->physicalSwitchDest->name }}
                </a>
                @elseif ($link->storageDeviceDest!=null)
                <a href="{{ route('admin.storage-devices.show', $link->storage_device_dest_id) }}">
                    {{ $link->storageDeviceDest->name }}
                </a>
                @elseif ($link->wifiTerminalDest!=null)
                <a href="{{ route('admin.wifi-terminals.show', $link->wifi_terminal_dest_id) }}">
                    {{ $link->wifiTerminalDest->name }}
                </a>
                @elseif ($link->workstationDest!=null)
                <a href="{{ route('admin.workstations.show', $link->workstation_dest_id) }}">
                    {{ $link->workstationDest->name }}
                </a>
                @elseif ($link->routerDest!=null)
                <a href="{{ route('admin.routers.show', $link->router_dest_id) }}">
                    {{ $link->routerDest->name }}
                </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalLink.fields.src_port') }}
            </th>
            <td colspan="2">
                {{ $link->dest_port }}
            </td>
        </tr>
    </tbody>
</table>
