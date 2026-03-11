@props([
    'information',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $information->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.information.fields.name') }}
            </th>
            <td>
            @if ($withLink)
            <a href="{{ route('admin.information.show', $information->id) }}">{{ $information->name }}</a>
            @else
            {{ $information->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.information.fields.description') }}
            </th>
            <td>
                {!! $information->description !!}
            </td>
        </tr>

        @hasModule('bpmn')
        @if($information->graphs()->count()>0)
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
                @foreach($information->graphs() as $graph)
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




