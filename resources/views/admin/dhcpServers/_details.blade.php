<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.dhcpServer.fields.name') }}
            </th>
            <td>
                {{ $dhcpServer->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.dhcpServer.fields.description') }}
            </th>
            <td>
                {!! $dhcpServer->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.dhcpServer.fields.address_ip') }}
            </th>
            <td>
                {{ $dhcpServer->address_ip }}
            </td>
        </tr>
    </tbody>
</table>
