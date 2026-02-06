<table class="table table-bordered table-striped table-report" id="{{ $relation->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.relation.fields.name') }}
            </th>
            <td>
                {{ $relation->name }}
            </td>
            <th>
                {{ trans('cruds.relation.fields.type') }}
            </th>
            <td>
                {{ $relation->type }}
            </td>
            <th>
                {{ trans('cruds.relation.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ",$relation->attributes) as $attribute)
                <span class="badge badge-info">{{ $attribute }}</span>
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.relation.fields.reference') }}
            </th>
            <td>
                {{ $relation->reference }}
            </td>
            <th>
                {{ trans('cruds.relation.fields.order_number') }}
            </th>
            <td>
                {{ $relation->order_number }}
            </td>
            <th>
                {{ trans('cruds.relation.fields.responsible') }}
            </th>
            <td>
                {{ $relation->responsible }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.relation.fields.source') }}
            </th>
            <td>
                <a href="{{ route('admin.entities.show', $relation->source_id) }}">
                {{ $relation->source->name ?? '' }}
                </a>
            </td>
            <th>
                {{ trans('cruds.relation.fields.destination') }}
            </th>
            <td colspan='4'>
                <a href="{{ route('admin.entities.show', $relation->destination_id) }}">
                {{ $relation->destination->name ?? '' }}
                </a>
            </td>
        </tr>

        <tr>
            <th>
                {{ trans('cruds.relation.fields.description') }}
            </th>
            <td colspan="5">
                {!! $relation->description !!}
            </td>
        </tr>
    </tbody>
</table>
