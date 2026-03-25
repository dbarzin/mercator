<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.gateway.fields.name') }}
            </th>
            <td>
                {{ $gateway->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.gateway.fields.description') }}
            </th>
            <td>
                {!! $gateway->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.gateway.fields.authentification') }}
            </th>
            <td>
                {{ $gateway->authentification }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.gateway.fields.ip') }}
            </th>
            <td>
                {{ $gateway->ip }}
            </td>
        </tr>
    </tbody>
</table>
