@props([
    'activity',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $activity->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.activity.fields.name') }}
            </th>
            <td>
            @if($withLink)
                <a href="{{ route('admin.activities.show', $activity->id) }}">{{ $activity->name }}</a>
            @else
                {{ $activity->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.activity.fields.description') }}
            </th>
            <td>
                {!! $activity->description !!}
            </td>
        </tr>

        <tr>
            <th>
                {{ trans('cruds.activity.fields.processes') }}
            </th>
            <td>
                @foreach($activity->processes as $process)
                    <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->name }}</a>
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            </td>
        </tr>

        <tr>
            <th>
                {{ trans('cruds.activity.fields.operations') }}
            </th>
            <td>
                @foreach($activity->operations as $operation)
                    <a href="{{ route('admin.operations.show', $operation->id) }}">{{ $operation->name }}</a>
                    @if (!$loop->last)
                        ,
                    @endif
                @endforeach
            </td>
        </tr>

        <tr>
            <th>
                {{ trans('cruds.activity.fields.applications') }}
            </th>
            <td colspan="5">
                @foreach($activity->applications as $application)
                    <a href="{{ route('admin.applications.show', $application->id) }}">
                    {{ $application->name }}
                    </a>
                    @if (!$loop->last)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
        @if($activity->graphs()->count()>0)
        <tr>
            <th>
               BPMN Graphs
            </th>
            <td colspan="5">
                @foreach($activity->graphs() as $graph)
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
