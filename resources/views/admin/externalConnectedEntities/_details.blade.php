@props([
    'network',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $externalConnectedEntity->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.externalConnectedEntity.fields.name') }}
        </th>
        <td>
        @if($withLink)
        <a href="{{ route('admin.external-connected-entities.show', $externalConnectedEntity) }}">{{ $externalConnectedEntity->name }}</a>
        @else
            {{ $externalConnectedEntity->name }}
        @endif
        </td>
        <th width="10%">
            {{ trans('cruds.externalConnectedEntity.fields.type') }}
        </th>
        <td>
            {{ $externalConnectedEntity->type }}
        </td>
    </tr>
    <tr>
        <th width="10%">
            {{ trans('cruds.externalConnectedEntity.fields.description') }}
        </th>
        <td colspan="3">
            {!! $externalConnectedEntity->description !!}
        </td>

    </tr>
    </tbody>
</table>
