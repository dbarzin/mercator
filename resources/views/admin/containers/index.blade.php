@extends('layouts.admin')
@section('content')
@can('container_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route('admin.containers.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.container.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.container.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th with="10">
                        </th>
                        <th>
                            {{ trans('cruds.container.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.container.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.container.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.container.fields.logical_servers') }}
                        </th>
                        <th>
                            {{ trans('cruds.container.fields.applications') }}
                        </th>
                        <th>
                            {{ trans('cruds.container.fields.databases') }}
                        </th>
                        <th width="10%">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($containers as $key => $container)
                        <tr data-entry-id="{{ $container->id }}"
                            @if(
                                ($container->description==null)||
                                ($container->type==null)||
                                ($container->applications->count()==0)||
                                ($container->logicalServers->count()==0)
                                )
                                    class="table-warning"
                            @endif
                            >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.containers.show', $container->id) }}">
                                    {{ $container->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $container->description ?? '' !!}
                            </td>
                            <td>
                                {{ $container->type ?? '' }}
                            </td>
                            <td>
                              @foreach($container->logicalServers as $logicalServer)
                                <a href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                                  {{ $logicalServer->name }}
                                </a>
                                  @if(!$loop->last)
                                  ,
                                  @endif
                              @endforeach
                            </td>
                            <td>
                              @foreach($container->applications as $application)
                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                  {{ $application->name }}
                                </a>
                                  @if(!$loop->last)
                                  ,
                                  @endif
                              @endforeach
                            </td>
                            <td>
                              @foreach($container->databases as $database)
                                <a href="{{ route('admin.databases.show', $database->id) }}">
                                  {{ $database->name }}
                                </a>
                                  @if(!$loop->last)
                                  ,
                                  @endif
                              @endforeach
                            </td>
                            <td nowrap>
                                @can('containers_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.containers.show', $container->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('container_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.containers.edit', $container->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('container_delete')
                                    <form action="{{ route('admin.containers.destroy', $container->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
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
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.container.title_singular"),
    'URL' => route('admin.containers.massDestroy'),
    'canDelete' => auth()->user()->can('site_delete') ? true : false
));
</script>
@endsection
