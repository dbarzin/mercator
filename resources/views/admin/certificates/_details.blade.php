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
            <a href="{{ route('admin.certificates.show', $certificate->id) }}">{{ $certificate->name }}</a>
        @else
            {{ $certificate->name }}
        @endif
        </td>
        <th width="10%">
            {{ trans('cruds.certificate.fields.type') }}
        </th>
        <td width="60%" colspan="3">
            {{ $certificate->type }}
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
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.logical_servers') }}
        </th>
        <td colspan="5">
            @foreach($certificate->logicalServers as $server)
                <a href="{{ route('admin.logical-servers.show', $server->id) }}">
                    {{ $server->name }}
                </a>
                @if(!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.applications') }}
        </th>
        <td colspan="5">
            @foreach($certificate->applications as $application)
                <a href="{{ route('admin.applications.show', $application->id) }}">
                    {{ $application->name }}
                </a>
                @if(!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>
    </tbody>
</table>
