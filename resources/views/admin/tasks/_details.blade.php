@props([
    'task',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $task->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.task.fields.name') }}
            </th>
            <td>
            @if($withLink)
                <a href="{{ route('admin.tasks.show', $task) }}">{{ $task->name }}</a>
            @else
                {{ $task->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.task.fields.description') }}
            </th>
            <td>
                {!! $task->description !!}
            </td>
        </tr>
    </tbody>
</table>
