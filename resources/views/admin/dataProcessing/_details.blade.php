@props([
    'dataProcessing',
    'withLink' => false,
])
<table class="table table-bordered table-striped table-report" id="{{ $dataProcessing->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.dataProcessing.fields.name') }}
        </th>
        <td width="50%">
        @if($withLink)
        <a href="{{ route('admin.data-processings.show', $dataProcessing->id) }}">{{ $dataProcessing->name }}</a>
        @else
            {{ $dataProcessing->name }}
        @endif
        </td>
        <th width="10%">
            {{ trans('cruds.dataProcessing.fields.legal_basis') }}
        </th>
        <td width="30%">
            {{ $dataProcessing->legal_basis }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.dataProcessing.fields.description') }}
        </th>
        <td colspan='3'>
            {!! $dataProcessing->description !!}
        </td>
    </tr>

    </tbody>
</table>
