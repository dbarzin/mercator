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
            @canShow($vlan)
            <a href=" {{ route('admin.vlans.show', $vlan) }}">{{ $vlan->name }}</a>
            @elsecanShow
            {{ $vlan->name }}
            @endcanShow
            @else
                {{ $vlan->name }}
            @endif
            </td>
            <th width="10%">
            {{ trans('cruds.vlan.fields.vlan_id') }}
            </th>
            <td>
            {{ $vlan->vlan_id }}
            </td>
            <th width="10%">
                {{ trans('cruds.vlan.fields.type') }}
            </th>
            <td>
                {{ $vlan->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.vlan.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $vlan->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.vlan.fields.description') }}
            </th>
            <td colspan="7">
                {!! $vlan->description !!}
            </td>
        </tr>
        @canAccess(App\Models\Subnetwork::class)
        <tr>
            <th>
                {{ trans('cruds.vlan.fields.subnetworks') }}
            </th>
            <td colspan="7">
                @foreach($vlan->subnetworks as $subnetwork)
                @canShow($subnetwork)
                <a href="/admin/subnetworks/{{ $subnetwork->id }}">{{ $subnetwork->name }}</a>
                @elsecanShow
                {{ $subnetwork->name }}
                @endcanShow
                @if (!$loop->last)
                ,
                @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
        @canAccess(App\Models\NetworkSwitch::class)
        <tr>
            <th>
                {{ trans('cruds.vlan.fields.network_switches') }}
            </th>
            <td colspan="7">
                @foreach($vlan->networkSwitches as $networkSwitch)
                @canShow($networkSwitch)
                <a href="/admin/network-switches/{{ $networkSwitch->id }}">{{ $networkSwitch->name }}</a>
                @elsecanShow
                {{ $networkSwitch->name }}
                @endcanShow
                @if (!$loop->last)
                ,
                @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
    </tbody>
</table>
