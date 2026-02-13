@props([
    'physicalSwitch',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $physicalSwitch->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.physicalSwitch.fields.name') }}
            </th>
            <td>
            @if ($withLink)
            <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">{{ $physicalSwitch->name }}</a>
            @else
            {{ $physicalSwitch->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalSwitch.fields.description') }}
            </th>
            <td>
                {!! $physicalSwitch->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalSwitch.fields.type') }}
            </th>
            <td>
                {{ $physicalSwitch->type }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalSwitch.fields.site') }}
            </th>
            <td>
                @if ($physicalSwitch->site!=null)
                    <a href="{{ route('admin.sites.show', $physicalSwitch->site->id) }}">
                    {{ $physicalSwitch->site->name ?? '' }}
                    </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalSwitch.fields.building') }}
            </th>
            <td>
                @if ($physicalSwitch->building!=null)
                    <a href="{{ route('admin.buildings.show', $physicalSwitch->building->id) }}">
                    {{ $physicalSwitch->building->name ?? '' }}
                    </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalSwitch.fields.bay') }}
            </th>
            <td>
                @if ($physicalSwitch->bay!=null)
                    <a href="{{ route('admin.bays.show', $physicalSwitch->bay->id) }}">
                    {{ $physicalSwitch->bay->name ?? '' }}
                    </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalSwitch.fields.network_switches') }}
            </th>
            <td>
                @foreach($physicalSwitch->networkSwitches as $networkSwitch)
                    <a href="{{ route('admin.network-switches.show', $networkSwitch->id) }}">
                    {{ $networkSwitch->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
