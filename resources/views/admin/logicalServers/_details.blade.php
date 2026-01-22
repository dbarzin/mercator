<table class="table table-bordered table-striped">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.logicalServer.fields.name') }}
        </th>
        <td>
            {{ $logicalServer->name }}
        </td>
        <th width="10%">
            {{ trans('cruds.logicalServer.fields.type') }}
        </th>
        <td>
            {{ $logicalServer->type }}
        </td>
        <th width="10%">
            {{ trans('cruds.logicalServer.fields.attributes') }}
        </th>
        <td>
            @foreach(explode(" ",$logicalServer->attributes) as $attribute)
                <span class="badge badge-info">{{ $attribute }}</span>
            @endforeach
        </td>
        <td width=10%>
            {{ $logicalServer->active ? "Active" : "" }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.logicalServer.fields.description') }}
        </th>
        <td colspan=5>
            {!! $logicalServer->description !!}
        </td>
        <td width="10%">
            @if ($logicalServer->icon_id === null)
                <img src='/images/lserver.png' width='120' height='120'>
            @else
                <img src='{{ route('admin.documents.show', $logicalServer->icon_id) }}' width='100'
                     height='100'>
            @endif
        </td>
    </tr>
    </tbody>
</table>
