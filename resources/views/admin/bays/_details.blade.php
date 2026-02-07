<table class="table table-bordered table-striped table-report" id="{{ $bay->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.bay.fields.name') }}
        </th>
        <td>
            {{ $bay->name }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.bay.fields.description') }}
        </th>
        <td>
            {!! $bay->description !!}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.bay.fields.room') }}
        </th>
        <td>
            @if ($bay->room!=null)
                <a href="{{ route('admin.buildings.show', $bay->room->id) }}">
                    {{ $bay->room->name ?? '' }}
                </a>
            @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.menu.physical_infrastructure.title_short') }}
        </th>
        <td>
            @foreach($bay->physicalServers as $physicalServer)
                <a href="{{ route('admin.physical-servers.show', $physicalServer->id) }}">{{ $physicalServer->name }}</a>
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->storageDevices as $storageDevice)
                <a href="{{ route('admin.storage-devices.show', $storageDevice->id) }}">{{ $storageDevice->name }}</a>
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->peripherals as $peripheral)
                <a href="{{ route('admin.peripherals.show', $peripheral->id) }}">{{ $peripheral->name }}</a>
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->physicalSwitches as $physicalSwitch)
                <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">{{ $physicalSwitch->name }}</a>
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->physicalRouters as $physicalRouter)
                <a href="{{ route('admin.physical-routers.show', $physicalRouter->id) }}">{{ $physicalRouter->name }}</a>
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach

            @foreach($bay->physicalSecurityDevices as $physicalSecurityDevice)
                <a href="{{ route('admin.physical-security-devices.show', $physicalSecurityDevice->id) }}">{{ $physicalSecurityDevice->name }}</a>
                @if(!$loop->last)
                    ,
                @else
                    <br>
                @endif
            @endforeach
        </td>
    </tr>
    </tbody>
</table>
