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
        @hasModule('bpmn')
        @if($task->graphs()->count()>0)
        <tr>
            <th>
                <span style="border: 2px solid grey;
                     color: darkred;
                     padding: 6px 14px;
                     border-radius: 6px;
                     display: inline-flex;
                     align-items: center;
                     gap: 8px;
                     font-weight: 600;
                     background: #eff6ff;">
                    <i class="bi bi-diagram-2-fill" style="font-size: 1.3em;"></i>
                    <span style="color: black;">
                        BPMN
                    </span>
                </span>
            </th>
            <td colspan="5" style="vertical-align: middle;">
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
        @endhasModule
    </tbody>
</table>
