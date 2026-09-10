<table class="table table-bordered table-striped table-report" id="{{ $router->getUID() }}">
    <tbody>
    <tr>
        <th width="10%">
            {{ trans('cruds.router.fields.name') }}
        </th>
        <td>
            {{ $router->name }}
        </td>
    </tr>
    <tr>
        <th width="10%">
            {{ trans('cruds.router.fields.type') }}
        </th>
        <td>
            {{ $router->type }}
        </td>
    </tr>
    <tr>
        <th width="10%">
            {{ trans('cruds.router.fields.attributes') }}
        </th>
        <td>
            @foreach(explode(" ", (string) $router->attributes) as $attribute)
                @if(strlen(trim($attribute)) > 0)
                    <span class="badge badge-info">{{ $attribute }}</span>
                @endif
            @endforeach
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.router.fields.description') }}
        </th>
        <td>
            {!! $router->description !!}
        </td>
    </tr>
    <tr>
        <th>
            {{ trans('cruds.router.fields.rules') }}
        </th>
        <td>
            {!! $router->rules !!}
        </td>
    </tr>
    @canAccess(App\Models\PhysicalRouter::class)
    <tr>
        <th>
            {{ trans('cruds.router.fields.physical_routers') }}
        </th>
        <td>
            @foreach($router->physicalRouters as $physicalRouter)
                @canShow($physicalRouter)
                <a href="{{ route('admin.physical-routers.show', $physicalRouter->id) }}">
                    {{ $physicalRouter->name }}
                </a>
                @elsecanShow
                    {{ $physicalRouter->name }}
                @endcanShow
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>
    @endcanAccess
    </tbody>
</table>
