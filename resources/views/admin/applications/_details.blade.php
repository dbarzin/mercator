@props([
    'application',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $application->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">{{ trans('cruds.application.fields.name') }}</th>
        <td>
        @if ($withLink)
        <a href='{{ route("admin.applications.show", $application->id) }}'>{{ $application->name }}</a>
        @else
        {{ $application->name }}
        @endif
        </td>
        <th width="10%">
            {{ trans('cruds.application.fields.application_block') }}
        </th>
        <td width="20%">
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
        <td width="10%" align="center">
            @if ($application->icon_id === null)
                <img src='/images/application.png' width='60' height='60'
                     alt="{{ $application->name }} icon"/>
            @else
                <img src='{{ route('admin.documents.show', $application->icon_id) }}' width='60'
                     height='60' alt="{{ $application->name }} icon"/>
            @endif
        </td>
    </tr>
    </tbody>
</table>
