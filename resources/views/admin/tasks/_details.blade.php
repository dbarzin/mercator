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
        @if($task->graphs()->count()>0)
        <tr>
            <th>
               <i class="bi bi-diagram-2"></i> &nbsp; BPMN
            </th>
            <td colspan="5">
                @foreach($task->graphs() as $graph)
                    <a href="{{ route('admin.bpmn.show', $graph->id) }}">
                    {{ $graph->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endif



    </tbody>
</table>
