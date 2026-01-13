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
        <tr>
            <th>
                {{ trans('cruds.task.fields.operations') }}
            </th>
            <td>
                @foreach($task->operations as $operation)
                    <a href="{{ route('admin.operations.show', $operation->id) }}">
                    {{ $operation->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
