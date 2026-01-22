<table class="table table-bordered table-striped">
    <tbody>
    <tr>
        <th width='10%'>
            {{ trans('cruds.subnetwork.fields.name') }}
        </th>
        <td>
            {{ $subnetwork->name }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.subnetwork.fields.description') }}
        </th>
        <td>
            {!! $subnetwork->description !!}
        </td>
    </tr>
    </tbody>
</table>
