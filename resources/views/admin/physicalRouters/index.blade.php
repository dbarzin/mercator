@extends('layouts.admin')
@section('content')
    @can('physical_router_create')
        <div style="margin-bottom: 10px;" class="row">
            <div class="col-lg-12">
                <a id="btn-new" class="btn btn-success" href="{{ route('admin.physical-routers.create') }}">
                    {{ trans('global.add') }} {{ trans('cruds.physicalRouter.title_singular') }}
                </a>
            </div>
        </div>
    @endcan
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.physicalRouter.title_singular') }} {{ trans('global.list') }}
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.building') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.bay') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.routers') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($physicalRouters as $physicalRouter)
                        <tr data-entry-id="{{ $physicalRouter->id }}"
                            @if (
                                ($physicalRouter->description===null)||
                                ($physicalRouter->type===null)||
                                ($physicalRouter->site_id===null)||
                                ($physicalRouter->building_id===null)
                                )
                                class="table-warning"
                                @endif
                        >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.physical-routers.show', $physicalRouter->id) }}">
                                    {{ $physicalRouter->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $physicalRouter->type ?? '' }}
                            </td>
                            <td>
                                {!! $physicalRouter->description ?? '' !!}
                            </td>
                            <td>
                                @if($physicalRouter->site!=null)
                                    <a href="{{ route('admin.sites.show', $physicalRouter->site->id) }}">
                                        {{ $physicalRouter->site->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if($physicalRouter->building!=null)
                                    <a href="{{ route('admin.buildings.show', $physicalRouter->building->id) }}">
                                        {{ $physicalRouter->building->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if($physicalRouter->bay!=null)
                                    <a href="{{ route('admin.bays.show', $physicalRouter->bay->id) }}">
                                        {{ $physicalRouter->bay->name ?? '' }}
                                    </a>
                                @endif
                            </td>
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
                            <td nowrap>
                                @can('physical_router_show')
                                    <a class="btn btn-xs btn-primary"
                                       href="{{ route('admin.physical-routers.show', $physicalRouter->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('physical_router_edit')
                                    <a class="btn btn-xs btn-info"
                                       href="{{ route('admin.physical-routers.edit', $physicalRouter->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('physical_router_delete')
                                    <form action="{{ route('admin.physical-routers.destroy', $physicalRouter->id) }}"
                                          method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');"
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
            'title' => trans("cruds.physicalRouter.title_singular"),
            'URL' => route('admin.physical-routers.massDestroy'),
            'canDelete' => auth()->user()->can('physical_router_delete') ? true : false
        ));
    </script>
@endsection
