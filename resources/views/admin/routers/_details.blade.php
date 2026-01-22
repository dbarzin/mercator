<table class="table table-bordered table-striped">
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
    <tr>
        <th>
            {{ trans('cruds.router.fields.physical_routers') }}
        </th>
        <td>
            @foreach($router->physicalRouters as $physicalRouter)
                <a href="{{ route('admin.physical-routers.show', $physicalRouter->id) }}">
                    {{ $physicalRouter->name }}
                </a>
                @if (!$loop->last)
                    ,
                @endif
            @endforeach
        </td>
    </tr>
    </tbody>
</table>
