@props([
    'bay',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $building->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.building.fields.name') }}
        </th>
        <td colspan="2">
        @if($withLink)
        <a href="{{ route('admin.buildings.show', $building->id) }}">{{ $building->name }}</a>
        @else
        {{ $building->name }}
        @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.building.fields.type') }}
        </th>
        <td colspan="">
            {{ $building->type }}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.building.fields.attributes') }}
        </th>
        <td colspan="2">
            @foreach(explode(" ",$building->attributes) as $attribute)
                <span class="badge badge-info">{{ $attribute }}</span>
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.building.fields.description') }}
        </th>
        <td>
            {!! $building->description !!}
        </td>
        <td width="10%">
            @if ($building->icon_id === null)
                <img src='/images/building.png' width='120' height='120'>
            @else
                <img src='{{ route('admin.documents.show', $building->icon_id) }}' width='120' height='120'>
            @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.building.fields.site') }}
        </th>
        <td colspan="2">
            @if ($building->site!=null)
                <a href="{{ route('admin.sites.show', $building->site->id) }}">
                    {{ $building->site->name ?? '' }}
                </a>
            @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.building.fields.parent') }}
        </th>
        <td colspan="2">
            @if ($building->building!=null)
                <a href="{{ route('admin.buildings.show', $building->building->id) }}">
                    {{ $building->building->name ?? '' }}
                </a>
            @endif
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.building.fields.children') }}
        </th>
        <td colspan="2">
            @foreach($building->buildings as $b)
                <a href="{{ route('admin.buildings.show', $b->id) }}">
                    {{ $b->name ?? '' }}
                </a>
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.building.fields.bays') }}
        </th>
        <td colspan="2">
            @foreach($building->roomBays as $bay)
                <a href="{{ route('admin.bays.show', $bay->id) }}">
                    {{ $bay->name ?? '' }}
                </a>
                @if ($building->roomBays->last()!=$bay)
                    ,
                @endif
            @endforeach
        </td>
    </tr>

    </tbody>
</table>
