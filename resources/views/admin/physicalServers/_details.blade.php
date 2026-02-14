@props([
    'physicalServer',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $physicalServer->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.physicalServer.fields.name') }}
        </th>
        <td width="50%">
        @if($withLink)
        <a href="{{ route('admin.physical-servers.show', $physicalServer) }}">{{ $physicalServer->name }}</a>
        @else
        {{ $physicalServer->name }}
        @endif
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
    <td width="10%" align="center">
        @if ($physicalServer->icon_id === null)
            <img src='/images/server.png' width='60' height='60'>
        @else
            <img src='{{ route('admin.documents.show', $physicalServer->icon_id) }}' width='60'
                 height='60'>
        @endif
    </td>
    </tbody>
</table>
