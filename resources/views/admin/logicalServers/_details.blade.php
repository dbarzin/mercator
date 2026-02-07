<table class="table table-bordered table-striped table-report" id="{{ $logicalServer->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.logicalServer.fields.name') }}
        </th>
        <td>
            @if($withLink)
            <a href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                {{ $logicalServer->name }}
            </a>
            @else
                {{ $logicalServer->name }}
            @endif
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

    <tr>
        <th width="16%">
            {{ trans('cruds.logicalServer.fields.operating_system') }}
        </th>
        <td width="16%">
            {{ $logicalServer->operating_system }}
        </td>
        <th width="16%">
            {{ trans('cruds.logicalServer.fields.install_date') }}
        </th>
        <td width="16%">
            {{ $logicalServer->install_date }}
        </td>
        <th width="16%">
            {{ trans('cruds.logicalServer.fields.update_date') }}
        </th>
        <td colspan="2">
            {{ $logicalServer->update_date }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.logicalServer.fields.cluster') }}
        </th>
        <td>
            @foreach($logicalServer->clusters as $cluster)
                <a href="{{ route('admin.clusters.show', $cluster->id) }}">
                    {{ $cluster->name ?? "" }}
                </a>
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
        <th>
            {{ trans('cruds.logicalServer.fields.environment') }}
        </th>
        <td>
            {{ $logicalServer->environment }}
        </td>
        <th>
            {{ trans('cruds.logicalServer.fields.address_ip') }}
        </th>
        <td colspan="2">
            {{ $logicalServer->address_ip }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.logicalServer.fields.net_services') }}
        </th>
        <td colspan="6">
            {{ $logicalServer->net_services }}
        </td>
    </tr>

    <tr>
        <th>
            {{ trans('cruds.logicalServer.fields.cpu') }}
        </th>
        <td>
            {{ $logicalServer->cpu }}
        </td>
        <th>
            <b>{{ trans('cruds.logicalServer.fields.memory') }}</b>
        </th>
        <td>
            {{ $logicalServer->memory }}
        </td>
        <th>
            {{ trans('cruds.logicalServer.fields.disk_used') }} /
            {{ trans('cruds.logicalServer.fields.disk') }}
        </th>
        <td colspan="2">
            {{ $logicalServer->disk_used }} /
            {{ $logicalServer->disk }} ( {{ ( $logicalServer->disk>0 ?   number_format(100 * $logicalServer->disk_used / $logicalServer->disk, 2) : "N/A") }} % )
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.logicalServer.fields.configuration') }}
        </th>
        <td colspan="7">
            {!! $logicalServer->configuration !!}
        </td>
    </tr>


    </tbody>
</table>
