<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.phone.fields.name') }}
            </th>
            <td>
                {{ $phone->name }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.phone.fields.type') }}
            </th>
            <td>
                {{ $phone->type }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.phone.fields.description') }}
            </th>
            <td>
                {!! $phone->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.phone.fields.address_ip') }}
            </th>
            <td>
                {{ $phone->address_ip }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.phone.fields.site') }}
            </th>
            <td>
                @if ($phone->site!==null)
                    <a href="{{ route('admin.sites.show', $phone->site_id) }}">{{ $phone->site->name }}</a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.phone.fields.building') }}
            </th>
            <td>
                @if ($phone->building!==null)
                    <a href="{{ route('admin.buildings.show', $phone->building_id) }}">{{ $phone->building->name }}</a>
                @endif
            </td>
        </tr>
    </tbody>
</table>
