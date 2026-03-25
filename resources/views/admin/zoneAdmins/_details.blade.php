<table class="table table-bordered table-striped table-report" id="{{ $zoneAdmin->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.zoneAdmin.fields.name') }}
            </th>
            <td>
                {{ $zoneAdmin->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.zoneAdmin.fields.description') }}
            </th>
            <td>
                {!! $zoneAdmin->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.zoneAdmin.fields.annuaires') }}
            </th>
            <td>
                @foreach($zoneAdmin->annuaires as $annuaire)
                <a href="{{ route('admin.annuaires.show', $annuaire->id) }}">
                    {{ $annuaire->name }}
                </a>
                @if ($zoneAdmin->annuaires->last()!=$annuaire)
                    ,
                @endif
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.zoneAdmin.fields.forests') }}
            </th>
            <td>
                @foreach($zoneAdmin->forestAds as $forestAd)
                <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                    {{ $forestAd->name ?? '' }}
                </a>
                @if ($zoneAdmin->forestAds->last()!=$forestAd)
                    ,
                @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
