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
                @canShow($dnsserver)
                    <a href="{{ route('admin.dnsservers.show', $dnsserver) }}">{{ $dnsserver->name }}</a>
                @elsecanShow
                    {{ $dnsserver->name }}
                @endcanShow
            @else
                {{ $dnsserver->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.dnsserver.fields.type') }}
            </th>
            <td width="20%">
                {{ $dnsserver->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.dnsserver.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $dnsserver->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.dnsserver.fields.description') }}
            </th>
            <td colspan="5">
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
