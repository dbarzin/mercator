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
        <td>
        @if($withLink)
            <a href="{{ route('admin.certificates.show', $certificate->id) }}">{{ $certificate->name }}</a>
        @else
            {{ $certificate->name }}
        @endif
        </td>
    </tr>
    <tr>
        <th width="10%">
            {{ trans('cruds.certificate.fields.type') }}
        </th>
        <td>
            {{ $certificate->type }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.description') }}
        </th>
        <td>
            {!! $certificate->description !!}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.start_validity') }}
        </th>
        <td>
            {{ $certificate->start_validity }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.end_validity') }}
        </th>
        <td>
            {{ $certificate->end_validity }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.last_notification') }}
        </th>
        <td>
            {{ $certificate->last_notification }}
            <br>
            {{ trans('cruds.certificate.fields.last_notification_helper') }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.certificate.fields.logical_servers') }}
        </th>
        <td>
            @foreach($certificate->logical_servers as $server)
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
        <td>
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
