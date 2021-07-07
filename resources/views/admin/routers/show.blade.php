@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.router.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.routers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
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
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.routers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection