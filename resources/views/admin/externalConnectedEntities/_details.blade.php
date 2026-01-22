<table class="table table-bordered table-striped">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.externalConnectedEntity.fields.name') }}
        </th>
        <td>
            {{ $externalConnectedEntity->name }}
        </td>
        <th width="10%">
            {{ trans('cruds.externalConnectedEntity.fields.type') }}
        </th>
        <td>
            {{ $externalConnectedEntity->type }}
        </td>
    </tr>
    <tr>
        <th width="10%">
            {{ trans('cruds.externalConnectedEntity.fields.description') }}
        </th>
        <td colspan="3">
            {!! $externalConnectedEntity->description !!}
        </td>

    </tr>
    </tbody>
</table>
