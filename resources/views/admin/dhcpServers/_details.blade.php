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
                @canShow($dhcpServer)
                    <a href="{{ route('admin.dhcp-servers.show', $dhcpServer) }}">{{ $dhcpServer->name }}</a>
                @elsecanShow
                    {{ $dhcpServer->name }}
                @endcanShow
            @else
                {{ $dhcpServer->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.dhcpServer.fields.type') }}
            </th>
            <td width="20%">
                {{ $dhcpServer->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.dhcpServer.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $dhcpServer->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.dhcpServer.fields.description') }}
            </th>
            <td colspan="5">
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
