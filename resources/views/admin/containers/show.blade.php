@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.containers.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=CONT_{{$container->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('container_edit')
        <a class="btn btn-info" href="{{ route('admin.containers.edit', $container->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('site_delete')
        <form action="{{ route('admin.containers.destroy', $container->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.container.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.container.fields.name') }}
                        </th>
                        <td>
                            {{ $container->name }}
                        </td>
                        <th width="10%">
                            <dt>{{ trans('cruds.container.fields.type') }}</dt>
                        </th>
                        <td width="10%">
                            {{ $container->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.container.fields.description') }}
                        </th>
                        <td colspan="2">
                            {!! $container->description !!}
                        </td>
                        <td width="10%">
                            @if ($container->icon_id === null)
                            <img src='/images/container.png' width='120' height='120'>
                            @else
                            <img src='{{ route('admin.documents.show', $container->icon_id) }}' width='120' height='120'>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.logical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                    <tr>
                        <th width='10%'>
                            {{ trans('cruds.container.fields.logical_servers') }}
                        </th>
                        <td>
                            @foreach($container->logicalServers as $server)
                                <a href="{{ route('admin.logical-servers.show', $server->id) }}">
                                {{ $server->name ?? '' }}
                                </a>
                                @if ($container->logicalServers->last()!=$server)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.application.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.container.fields.applications') }}
                        </th>
                        <td width="40%">
                            @foreach($container->applications as $application)
                                <a href="{{ route('admin.applications.show', $application->id) }}">
                                {{ $application->name ?? '' }}
                                </a>
                                @if ($container->applications->last()!=$application)
                                ,
                                @endif
                            @endforeach
                        </td>
                        <th width="10%">
                            {{ trans('cruds.container.fields.databases') }}
                        </th>
                        <td width="40%">
                            @foreach($container->databases as $database)
                                <a href="{{ route('admin.databases.show', $database->id) }}">
                                {{ $database->name ?? '' }}
                                </a>
                                @if ($container->databases->last()!=$database)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $container->created_at ? $container->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $container->updated_at ? $container->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.containers.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
