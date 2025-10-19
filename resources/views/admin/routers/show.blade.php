@extends('layouts.admin')
@section('content')
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.routers.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=ROUTER_{{$router->id}}">
            {{ trans('global.explore') }}
        </a>

        @can('entity_edit')
            <a class="btn btn-info" href="{{ route('admin.routers.edit', $router->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('entity_delete')
            <form action="{{ route('admin.routers.destroy', $router->id) }}" method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan
    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.router.title') }}
        </div>

        <div class="card-body">
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
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $router->created_at ? $router->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $router->updated_at ? $router->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.routers.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
