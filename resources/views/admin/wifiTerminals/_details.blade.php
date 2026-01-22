<table class="table table-bordered table-striped">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.wifiTerminal.fields.name') }}
        </th>
        <td>
            {{ $wifiTerminal->name }}
        </td>
        <th width="10%">
            {{ trans('cruds.wifiTerminal.fields.type') }}
        </th>
        <td>
            {{ $wifiTerminal->type }}
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
