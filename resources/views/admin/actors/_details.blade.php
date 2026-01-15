@props([
    'actor',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $actor->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.actor.fields.name') }}
            </th>
            <td>
            @if($withLink)
            <a href="{{ route('admin.actors.show', $actor->id) }}">{{ $actor->name }}</a>
            @else
                {{ $actor->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.actor.fields.contact') }}
            </th>
            <td>
                {{ $actor->contact }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.actor.fields.nature') }}
            </th>
            <td>
                {{ $actor->nature }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.actor.fields.type') }}
            </th>
            <td>
                {{ $actor->type }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.actor.fields.operations') }}
            </th>
            <td>
            @foreach($actor->operations as $operation)
                <a href="{{ route('admin.operations.show', $operation->id) }}">{{ $operation->name }}</a>
                @if(!$loop->last)
                ,
                @endif
            @endforeach
            </td>
        </tr>
        @if($actor->graphs()->count()>0)
        <tr>
            <th>
               <i class="bi bi-diagram-2"></i> &nbsp; BPMN
            </th>
            <td>
                @foreach($actor->graphs() as $graph)
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
