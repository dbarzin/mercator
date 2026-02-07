<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width='10%'>
                {{ trans('cruds.forestAd.fields.name') }}
            </th>
            <td>
                {{ $forestAd->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.forestAd.fields.description') }}
            </th>
            <td>
                {!! $forestAd->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.forestAd.fields.zone_admin') }}
            </th>
            <td>
                @if ($forestAd->zone_admin_id!=null)
                <a href="{{ route('admin.zone-admins.show', $forestAd->zone_admin->id) }}">
                {{ $forestAd->zone_admin->name ?? '' }}
                </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.forestAd.fields.domaines') }}
            </th>
            <td>
                @foreach($forestAd->domaines as $domaine)
                <a href="{{ route('admin.domaine-ads.show', $domaine->id) }}">
                {{ $domaine->name }}
                </a>
                @if ($forestAd->domaines->last()!=$domaine)
                ,
                @endif
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
