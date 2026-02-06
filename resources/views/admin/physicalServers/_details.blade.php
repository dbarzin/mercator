<table class="table table-bordered table-striped table-report" id="{{ $physicalServer->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.physicalServer.fields.name') }}
        </th>
        <td width="50%">
            {{ $physicalServer->name }}
        </td>
        <th width="10%">
            {{ trans('cruds.physicalServer.fields.type') }}
        </th>
        <td width="30%" colspan="2">
            {{ $physicalServer->type }}
        </td>
    </tr>
    <th>
        {{ trans('cruds.physicalServer.fields.description') }}
    </th>
    <td colspan="3">
        {!! $physicalServer->description !!}
    </td>
    <td width="10%">
        @if ($physicalServer->icon_id === null)
            <img src='/images/server.png' width='120' height='120'>
        @else
            <img src='{{ route('admin.documents.show', $physicalServer->icon_id) }}' width='100'
                 height='100'>
        @endif
    </td>
    </tbody>
</table>
