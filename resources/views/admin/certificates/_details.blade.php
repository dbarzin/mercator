@props([
    'certificate',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $certificate->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.certificate.fields.name') }}
        </th>
        <td width="20%">
        @if($withLink)
            @canShow($certificate)
            <a href="{{ route('admin.certificates.show', $certificate->id) }}">{{ $certificate->name }}</a>
            @elsecanShow
            {{ $certificate->name }}
            @endcanShow
        @else
            {{ $certificate->name }}
        @endif
        </td>
        <th width="10%">
            {{ trans('cruds.certificate.fields.type') }}
        </th>
        <td width="20%">
            {{ $certificate->type }}
        </td>
        <th width="10%">
            {{ trans('cruds.certificate.fields.attributes') }}
        </th>
        <td width="30%" colspan="2">
            @foreach(explode(" ", (string) $certificate->attributes) as $attribute)
                @if(strlen(trim($attribute)) > 0)
                    <span class="badge badge-info">{{ $attribute }}</span>
                @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.description') }}
        </th>
        <td colspan="5">
            {!! $certificate->description !!}
        </td>
    </tr>
    <tr>
        <th width="10%">
            {{ trans('cruds.certificate.fields.start_validity') }}
        </th>
        <td width="20%">
            {{ $certificate->start_validity }}
        </td>
        <th width="10%">
            {{ trans('cruds.certificate.fields.end_validity') }}
        </th>
        <td width="20%">
            {{ $certificate->end_validity }}
        </td>
        <th width="10%">
            {{ trans('cruds.certificate.fields.last_notification') }}
        </th>
        <td width="30%">
            {{ $certificate->last_notification }}
            <br>
            {{ trans('cruds.certificate.fields.last_notification_helper') }}
        </td>
    </tr>
    @canAccess(App\Models\LogicalServer::class)
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.logical_servers') }}
        </th>
        <td colspan="5">
            @foreach($certificate->logicalServers as $server)
                @canShow($server)
                    <a href="{{ route('admin.logical-servers.show', $server->id) }}">
                        {{ $server->name }}
                    </a>
                @elsecanShow
                    {{ $server->name }}
                @endcanShow
                @if(!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>
    @endcanAccess
    @canAccess(App\Models\Application::class)
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.applications') }}
        </th>
        <td colspan="5">
            @foreach($certificate->applications as $application)
                @canShow($application)
                    <a href="{{ route('admin.applications.show', $application->id) }}">
                        {{ $application->name }}
                    </a>
                @elsecanShow
                    {{ $application->name }}
                @endcanShow
                @if(!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>
    @endcanAccess
    </tbody>
</table>
