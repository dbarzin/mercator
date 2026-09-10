<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.gateway.fields.name') }}
            </th>
            <td>
                {{ $gateway->name }}
            </td>
            <th width="10%">
                {{ trans('cruds.gateway.fields.type') }}
            </th>
            <td width="20%">
                {{ $gateway->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.gateway.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $gateway->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.gateway.fields.description') }}
            </th>
            <td colspan="5">
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
