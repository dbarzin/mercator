@props([
    'physicalSwitch',
    'withLink' => false
])
<table class="table table-bordered table-striped table-report" id="{{ $physicalRouter->getUID() }}">
    <tbody>
        <tr>
            <th width="10%">
                {{ trans('cruds.physicalRouter.fields.name') }}
            </th>
            <td>
            @if($withLink)
            <a href="{{ route('admin.physical-routers.show', $physicalRouter->id) }}">{{ $physicalRouter->name }}</a>
            @else
            {{ $physicalRouter->name }}
            @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalRouter.fields.type') }}
            </th>
            <td>
                {{ $physicalRouter->type }}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalRouter.fields.description') }}
            </th>
            <td>
                {!! $physicalRouter->description !!}
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalRouter.fields.site') }}
            </th>
            <td>
                @if ($physicalRouter->site!=null)
                    <a href="{{ route('admin.sites.show', $physicalRouter->site->id) }}">
                    {{ $physicalRouter->site->name ?? '' }}
                    </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalRouter.fields.building') }}
            </th>
            <td>
                @if ($physicalRouter->building!=null)
                    <a href="{{ route('admin.buildings.show', $physicalRouter->building->id) }}">
                    {{ $physicalRouter->building->name ?? '' }}
                    </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalRouter.fields.bay') }}
            </th>
            <td>
                @if ($physicalRouter->bay!=null)
                    <a href="{{ route('admin.bays.show', $physicalRouter->bay->id) }}">
                    {{ $physicalRouter->bay->name ?? '' }}
                    </a>
                @endif
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalRouter.fields.routers') }}
            </th>
            <td>
                @foreach($physicalRouter->routers as $router)
                    <a href="{{ route('admin.routers.show', $router->id) }}">
                    {{ $router->name }}
                    @if(!$loop->last)
                    ,
                    @endif
                    </a>
                @endforeach
            </td>
        </tr>
        <tr>
            <th>
                {{ trans('cruds.physicalRouter.fields.vlan') }}
            </th>
            <td>
                @foreach($physicalRouter->vlans as $vlan)
                    <a href="{{ route('admin.vlans.show', $vlan->id) }}">
                    {{ $vlan->name }}
                    @if(!$loop->last)
                    ,
                    @endif
                    </a>
                @endforeach
            </td>
        </tr>
    </tbody>
</table>
