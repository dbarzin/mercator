@props([
    'networkSwitch',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $networkSwitch->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.networkSwitch.fields.name') }}
            </th>
            <td>
            @if ($withLink)
            <a href="{{ route('admin.network-switches.show', $networkSwitch->id) }}">{{ $networkSwitch->name }}</a>
            @else
            {{ $networkSwitch->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.networkSwitch.fields.description') }}
            </th>
            <td>
                {!! $networkSwitch->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.networkSwitch.fields.ip') }}
            </th>
            <td>
                {{ $networkSwitch->ip }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.networkSwitch.fields.physical_switches') }}
            </th>
            <td>
                @foreach($networkSwitch->physicalSwitches as $physicalSwitch)
                    <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">
                    {{ $physicalSwitch->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
