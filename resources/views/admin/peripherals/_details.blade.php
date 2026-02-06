<table class="table table-bordered table-striped table-report" id="{{ $peripheral->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.peripheral.fields.name') }}
            </th>
            <td width="40%">
                {{ $peripheral->name }}
            </td>
            <th width="10%">
                {{ trans('cruds.peripheral.fields.domain') }}
            </th>
            <td width="10%">
                {{ $peripheral->domain }}
            </td>
            <th width="10%">
                {{ trans('cruds.peripheral.fields.type') }}
            </th>
            <td width="10%">
                {{ $peripheral->type }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.peripheral.fields.description') }}
            </th>
            <td colspan='4'>
                {!! $peripheral->description !!}
            </td>
            <td width="10%">
                @if ($peripheral->icon_id === null)
                <img src='/images/peripheral.png' width='120' height='120'>
                @else
                <img src='{{ route('admin.documents.show', $peripheral->icon_id) }}' width='100' height='100'>
                @endif
            </td>

        </tr>
    </tbody>
</table>
