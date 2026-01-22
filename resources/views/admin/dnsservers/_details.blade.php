<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.dnsserver.fields.name') }}
            </th>
            <td>
                {{ $dnsserver->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.dnsserver.fields.description') }}
            </th>
            <td>
                {!! $dnsserver->description !!}
            </td>
        </tr>
        <tr>
            <th width="10%">
                {{ trans('cruds.dnsserver.fields.address_ip') }}
            </th>
            <td>
                {{ $dnsserver->address_ip }}
            </td>
        </tr>
    </tbody>
</table>
