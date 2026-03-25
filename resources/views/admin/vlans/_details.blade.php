@props([
    'vlan',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $vlan->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.vlan.fields.name') }}
            </th>
            <td>
            @if ($withLink)
            <a href=" {{ route('admin.vlans.show', $vlan) }}">{{ $vlan->name }}</a>
            @else
                {{ $vlan->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.vlan.fields.description') }}
            </th>
            <td>
                {!! $vlan->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.vlan.fields.subnetworks') }}
            </th>
            <td>
                @foreach($vlan->subnetworks as $subnetwork)
                <a href="/admin/subnetworks/{{ $subnetwork->id }}">{{ $subnetwork->name }}</a>
                @if (!$loop->last)
                ,
                @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.vlan.fields.network_switches') }}
            </th>
            <td>
                @foreach($vlan->networkSwitches as $networkSwitch)
                <a href="/admin/network-switches/{{ $networkSwitch->id }}">{{ $networkSwitch->name }}</a>
                @if (!$loop->last)
                ,
                @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
