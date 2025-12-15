@extends('layouts.admin')
@section('content')
    @can('router_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route("admin.routers.create") }}">
                    {{ trans('global.add') }} {{ trans('cruds.router.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.router.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.router.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.router.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.router.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.router.fields.physical_routers') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($routers as $router)
                        <tr data-entry-id="{{ $router->id }}"
                            @if (
                                ($router->description===null)
                                )
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.routers.show', $router->id) }}">
                                    {{ $router->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $router->type ?? '' }}
                            </td>
                            <td>
                                {!! $router->description ?? '' !!}
                            </td>
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
                            <td nowrap>
                                @can('router_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.routers.show', $router->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('router_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.routers.edit', $router->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('router_delete')
                                    <form action="{{ route('admin.routers.destroy', $router->id) }}" method="POST"
                                          onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
                                          style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger"
                                               value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    @parent
    <script>
        @include('partials.datatable', array(
            'id' => '#dataTable',
            'title' => trans("cruds.router.title_singular"),
            'URL' => route('admin.routers.massDestroy'),
            'canDelete' => auth()->user()->can('router_delete') ? true : false
        ));
    </script>
@endsection
