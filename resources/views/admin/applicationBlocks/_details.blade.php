@props([
    'applicationBlock',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $applicationBlock->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.applicationBlock.fields.name') }}
            </th>
            <td>
            @if($withLink)
            <a href="{{ route('admin.application-blocks.show',$applicationBlock->id) }}">{{ $applicationBlock->name }}</a>
            @else
            {{ $applicationBlock->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationBlock.fields.description') }}
            </th>
            <td>
                {!! $applicationBlock->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.applicationBlock.fields.responsible') }}
            </th>
            <td>
                {{ $applicationBlock->responsible }}
            </td>
        </tr>
        <tr>
            <th>
                Applications
            </th>
            <td>
                @foreach($applicationBlock->applications as $key => $application)
                    <a href="{{ route('admin.applications.show',$application->id) }}">{{ $application->name }}</a>
                    @if(!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
