<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.lan.fields.name') }}
            </th>
            <td>
                {{ $lan->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.lan.fields.description') }}
            </th>
            <td>
                {{ $lan->description }}
            </td>
        </tr>
    </tbody>
</table>
