@props([
    'peripheral',
    'withLink' => false,
])

<table class="table table-bordered table-striped table-report" id="{{ $peripheral->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.peripheral.fields.name') }}
            </th>
            <td width="40%">
            @if($withLink)
                @canShow($peripheral)
                    <a href="{{ route('admin.peripherals.show', $peripheral) }}">{{ $peripheral->name }}</a>
                @elsecanShow
                    {{ $peripheral->name }}
                @endcanShow
            @else
                {{ $peripheral->name }}
            @endif
            </td>
            <th width="10%">
                {{ trans('cruds.peripheral.fields.domain') }}
            </th>
            <td width="10%">
                @if($peripheral->domain!=null)
                    @canShow($peripheral->domain)
                        <a href="{{ route('admin.domains.show', $peripheral->domain->id) }}">
                            {{ $peripheral->domain->name }}
                        </a>
                    @elsecanShow
                        {{ $peripheral->domain->name }}
                    @endcanShow
                @endif
            </td>
            <th width="10%">
                {{ trans('cruds.peripheral.fields.type') }}
            </th>
            <td width="10%">
                {{ $peripheral->type }}
            </td>
            <th width="10%">
                {{ trans('cruds.peripheral.fields.attributes') }}
            </th>
            <td>
                @foreach(explode(" ", (string) $peripheral->attributes) as $attribute)
                    @if(strlen(trim($attribute)) > 0)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.peripheral.fields.description') }}
            </th>
            <td colspan='4'>
                {!! $peripheral->description !!}
            </td>
            <td width="10%" align="center">
                @if ($peripheral->icon_id === null)
                <img src='/images/peripheral.png' width='60' height='60'>
                @else
                <img src='{{ route('admin.documents.show', $peripheral->icon_id) }}' width='60' height='60'>
                @endif
            </td>

        </tr>
    </tbody>
</table>
