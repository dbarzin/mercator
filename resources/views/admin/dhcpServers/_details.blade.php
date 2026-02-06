@props([
    'dhcpServer',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $dhcpServer->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.dhcpServer.fields.name') }}
            </th>
            <td>
            @if($withLink)
            <a href="{{ route('admin.dhcp-servers.show', $dhcpServer) }}">{{ $dhcpServer->name }}</a>
            @else
            {{ $dhcpServer->name }}
            @endif
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
