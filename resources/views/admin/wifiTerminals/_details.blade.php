@props([
    'wifiTerminal',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $wifiTerminal->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.wifiTerminal.fields.name') }}
        </th>
        <td>
        @if($withLink)
            @canShow($wifiTerminal)
                <a href="{{ route('admin.wifi-terminals.show', $wifiTerminal) }}">{{ $wifiTerminal->name }}</a>
            @elsecanShow
                {{ $wifiTerminal->name }}
            @endcanShow
        @else
            {{ $wifiTerminal->name }}
        @endif
        </td>
        <th width="10%">
            {{ trans('cruds.wifiTerminal.fields.type') }}
        </th>
        <td>
            {{ $wifiTerminal->type }}
        </td>
    </tr>
    <tr>
        <th width="10%">
            {{ trans('cruds.wifiTerminal.fields.attributes') }}
        </th>
        <td colspan="3">
            @foreach(explode(" ", (string) $wifiTerminal->attributes) as $attribute)
                @if(strlen(trim($attribute)) > 0)
                    <span class="badge badge-info">{{ $attribute }}</span>
                @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.wifiTerminal.fields.description') }}
        </th>
        <td colspan="3">
            {!! $wifiTerminal->description !!}
        </td>
    </tr>
    <tr>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.wifiTerminal.fields.address_ip') }}
        </th>
        <td colspan="3">
            {{ $wifiTerminal->address_ip ?? '' }}
        </td>
    </tr>
    </tbody>
</table>
