<table class="table table-bordered table-striped table-report" id="{{ $application->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">{{ trans('cruds.application.fields.name') }}</th>
        <td>
            {{ $application->name }}
        </td>
        <th width="10%">
            {{ trans('cruds.application.fields.application_block') }}
        </th>
        <td width="10%">
            @if ($application->applicationBlock!=null)
                <a href='{{ route("admin.application-blocks.show", $application->applicationBlock->id) }}'>{{ $application->applicationBlock->name }}</a>
            @endif
        </td>
        <th width="10%">
            {{ trans('cruds.application.fields.attributes') }}
        </th>
        <td width="10%">
            {{ $application->attributes }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.application.fields.description') }}
        </th>
        <td colspan="4">
            {!! $application->description !!}
        </td>
        <td width="10%">
            @if ($application->icon_id === null)
                <img src='/images/application.png' width='120' height='120'
                     alt="{{ $application->name }} icon"/>
            @else
                <img src='{{ route('admin.documents.show', $application->icon_id) }}' width='100'
                     height='100' alt="{{ $application->name }} icon"/>
            @endif
        </td>
    </tr>
    </tbody>
</table>
