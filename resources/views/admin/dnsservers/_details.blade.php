@props([
    'dnsserver',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $dnsserver->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.dnsserver.fields.name') }}
            </th>
            <td>
            @if ($withLink)
                <a href="{{ route('admin.dnsservers.show', $dnsserver) }}">{{ $dnsserver->name }}</a>
            @else
                {{ $dnsserver->name }}
            @endif
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
