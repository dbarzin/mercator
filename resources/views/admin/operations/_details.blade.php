@props([
    'operation',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $operation->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.operation.fields.name') }}
            </th>
            <td>
            @if ($withLink)
            <a href="{{ route('admin.operations.show', $operation->id) }}">{{ $operation->name }}</a>
            @else
            {{ $operation->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.operation.fields.description') }}
            </th>
            <td>
                {!! $operation->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.operation.fields.process') }}
            </th>
            <td>
                @if ($operation->process!=null)
                    <a href="{{ route('admin.processes.show',$operation->process->id) }}">
                        {{ $operation->process->name ?? '' }}
                    </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.operation.fields.activities') }}
            </th>
            <td>
                @foreach($operation->activities as $activity)
                    <a href="{{ route('admin.activities.show', $activity->id) }}">
                        {{ $activity->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.operation.fields.actors') }}
            </th>
            <td>
                @foreach($operation->actors as $actor)
                    <a href="{{ route('admin.actors.show', $actor->id) }}">
                        {{ $actor->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.operation.fields.tasks') }}
            </th>
            <td>
                @foreach($operation->tasks as $task)
                    <a href="{{ route('admin.tasks.show', $task->id) }}">
                        {{ $task->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @hasModule('bpmn')
        @if($operation->graphs()->count()>0)
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
                    <span style="color: black;">BPMN</span>
                </span>
            </th>
            <td colspan="5" style="vertical-align: middle;">
                @foreach($operation->graphs() as $graph)
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

