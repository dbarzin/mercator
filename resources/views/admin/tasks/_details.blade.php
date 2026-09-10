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
                @canShow($task)
                <a href="{{ route('admin.tasks.show', $task) }}">{{ $task->name }}</a>
                @elsecanShow
                {{ $task->name }}
                @endcanShow
            @else
                {{ $task->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.task.fields.type') }}
            </th>
            <td width="20%">
                {{ $task->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.task.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $task->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.task.fields.description') }}
            </th>
            <td colspan="5">
                {!! $task->description !!}
            </td>
        </tr>
        @canAccess(App\Models\Operation::class)
        <tr>
            <th>
                {{ trans('cruds.task.fields.operations') }}
            </th>
            <td>
                @foreach($task->operations as $operation)
                    @canShow($operation)
                    <a href="{{ route('admin.operations.show', $operation->id) }}">
                    {{ $operation->name }}
                    </a>
                    @elsecanShow
                        {{ $operation->name }}
                    @endcanShow
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endcanAccess
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
                    @canShow($graph)
                        <a href="{{ route('admin.bpmn.show', $graph->id) }}">
                            {{ $graph->name }}
                        </a>
                    @elsecanShow
                        {{ $graph->name }}
                    @endcanShow
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @endif
    </tbody>
</table>
