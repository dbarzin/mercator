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
            @canShow($actor)
            <a href="{{ route('admin.actors.show', $actor->id) }}">{{ $actor->name }}</a>
            @elsecanShow
            {{ $actor->name }}
            @endcanShow
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
                {{ trans('cruds.actor.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $actor->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        @canAccess(App\Models\Operation::class)
        <tr>
            <th>
                {{ trans('cruds.actor.fields.operations') }}
            </th>
            <td>
            @foreach($actor->operations as $operation)
                @canShow($operation)
                    <a href="{{ route('admin.operations.show', $operation->id) }}">{{ $operation->name }}</a>
                @elsecanShow
                    {{ $operation->name }}
                @endcanShow
                @if(!$loop->last)
                ,
                @endif
            @endforeach
            </td>
        </tr>
        @endcanAccess
        @if($actor->graphs()->count()>0)
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
            <td style="vertical-align: middle;">
                @foreach($actor->graphs() as $graph)
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
