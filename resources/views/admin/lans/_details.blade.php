<table class="table table-bordered table-striped table-report">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.lan.fields.name') }}
            </th>
            <td>
                {{ $lan->name }}
            </td>
            <th width="10%">
                {{ trans('cruds.lan.fields.type') }}
            </th>
            <td width="20%">
                {{ $lan->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.lan.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $lan->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.lan.fields.description') }}
            </th>
            <td colspan="5">
                {{ $lan->description }}
            </td>
        </tr>
    </tbody>
</table>
