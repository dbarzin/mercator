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
            <th width="10%">
                {{ trans('cruds.physicalSwitch.fields.type') }}
            </th>
            <td>
                {{ $physicalSwitch->type }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalSwitch.fields.description') }}
            </th>
            <td colspan="2">
                {!! $physicalSwitch->description !!}
            </td>
            <td style="text-align: center; width: 10%">
                @if ($physicalSwitch->icon_id === null)
                    <img src='/images/switch.png' width='60' height='60'>
                @else
                    <img src='{{ route('admin.documents.show', $physicalSwitch->icon_id) }}' width='60' height='60'>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalSwitch.fields.site') }}
            </th>
            <td colspan="3">
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
            <td colspan="3">
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
            <td colspan="3">
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
            <td colspan="3">
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
