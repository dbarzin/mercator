<table class="table table-bordered table-striped">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.storageDevice.fields.name') }}
            </th>
            <td width="40%" colspan="2">
                {{ $storageDevice->name }}
            </td>
            <th width="10%">
                {{ trans('cruds.storageDevice.fields.type') }}
            </th>
            <td width="40%" colspan="2">
                {{ $storageDevice->type }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.storageDevice.fields.description') }}
            </th>
            <td colspan='5'>
                {!! $storageDevice->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.storageDevice.fields.address_ip') }}
            </th>
            <td colspan="5">
                {{ $storageDevice->address_ip }}
            </td>
        </tr>
        <tr>
            <th width="10%">
                {{ trans('cruds.storageDevice.fields.site') }}
            </th>
            <td width="22%">
                @if ($storageDevice->site!=null)
                    <a href="{{ route('admin.sites.show', $storageDevice->site->id) }}">
                    {{ $storageDevice->site->name ?? '' }}
                    </a>
                @endif
            </td>
            <th width="10%">
                {{ trans('cruds.storageDevice.fields.building') }}
            </th>
            <td width="22%">
                @if ($storageDevice->building!=null)
                    <a href="{{ route('admin.buildings.show', $storageDevice->building->id) }}">
                    {{ $storageDevice->building->name ?? '' }}
                    </a>
                @endif
            </td>
            <th width="10%">
                {{ trans('cruds.storageDevice.fields.bay') }}
            </th>
            <td width="22%">
                @if ($storageDevice->bay!=null)
                    <a href="{{ route('admin.bays.show', $storageDevice->bay->id) }}">
                    {{ $storageDevice->bay->name ?? '' }}
                    </a>
                @endif
            </td>
        </tr>
    </tbody>
</table>
