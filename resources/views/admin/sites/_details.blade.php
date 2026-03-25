@props([
    'site',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $site->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.site.fields.name') }}
            </th>
            <td colspan="2">
            @if($withLink)
            <a href="{{ route('admin.sites.show', $site->id) }}">{{ $site->name }}</a>
            @else
            {{ $site->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.site.fields.description') }}
            </th>
            <td>
                {!! $site->description !!}
            </td>
            <td width="10%" align="center">
                @if ($site->icon_id === null)
                <img src='/images/site.png' width='60' height='60'>
                @else
                <img src='{{ route('admin.documents.show', $site->icon_id) }}' width='60' height='60'>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.site.fields.buildings') }}
            </th>
            <td colspan="2">
                @foreach($site->buildings as $building)
                    <a href="{{ route('admin.buildings.show', $building->id) }}">
                    {{ $building->name ?? '' }}
                    </a>
                    @if ($site->buildings->last()!=$building)
                    ,
                    @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
