@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.databases.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$database->getUID()}}">
        {{ trans('global.explore') }}
    </a>

    @can('database_edit')
        <a class="btn btn-info" href="{{ route('admin.databases.edit', $database->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('entity_delete')
        <form action="{{ route('admin.databases.destroy', $database->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.database.title') }}
    </div>
    <div class="card-body">
        @include('admin.databases._details', [
            'database' => $database,
            'withLink' => false,
        ])
    </div>


    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans('cruds.flux.title') }}
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <table class="table table-bordered table-striped table-report">
            <tbody>
            <tr>
                <th width="20%">
                    {{ trans('cruds.flux.fields.name') }}
                </th>
                <th width="10%">
                    {{ trans('cruds.flux.fields.nature') }}
                </th>
                <th width="10%">
                    {{ trans('cruds.flux.fields.attributes') }}
                </th>
                <th width="20%">
                    {{ trans('cruds.flux.fields.module_source') }}
                </th>
                <th width="20%">
                    {{ trans('cruds.flux.fields.module_dest') }}
                </th>
                <th width="20%">
                    {{ trans('cruds.flux.fields.information') }}
                </th>
            </tr>
            @foreach($database->databaseSourceFluxes->union($database->databaseDestFluxes) as $flux)
            <tr>
                <td>
                    <a href="{{ route('admin.fluxes.show', $flux->id) }}">{{ $flux->name }}</a>
                </td>
                <td>
                   {{ $flux->nature }}
                </td>
                <td>
                    @foreach(explode(" ",$flux->attributes) as $attribute)
                        <span class="badge badge-info">{{ $attribute }}</span>
                    @endforeach
                </td>
                <td>
                    @if ($flux->application_source!=null)
                        <a href="{{ route('admin.applications.show',$flux->application_source->id) }}">
                            {{ $flux->application_source->name }}
                        </a>
                    @endif
                    @if($flux->service_source!=null)
                        <a href="{{ route('admin.application-services.show', $flux->service_source->id) }}">
                            {{ $flux->service_source->name }}
                        </a>
                    @endif
                    @if ($flux->module_source!=null)
                        <a href="{{ route('admin.application-modules.show', $flux->module_source->id) }}">
                            {{ $flux->module_source->name }}
                        </a>
                    @endif
                    @if ($flux->database_source!=null)
                        <a href="{{ route('admin.databases.show',$flux->database_source->id) }}">
                            {{ $flux->database_source->name }}
                        </a>
                    @endif
                </td>
                <td>
                    @if ($flux->application_dest!=null)
                        <a href="{{ route('admin.applications.show',$flux->application_dest->id) }}">
                            {{ $flux->application_dest->name }}
                        </a>
                    @endif
                    @if ($flux->service_dest!=null)
                        <a href="{{ route('admin.application-services.show', $flux->service_dest->id) }}">
                            {{ $flux->service_dest->name }}
                        </a>
                    @endif
                    @if ($flux->module_dest!=null)
                        <a href="{{ route('admin.application-modules.show', $flux->module_dest->id) }}">
                            {{ $flux->module_dest->name }}
                        </a>
                    @endif
                    @if ($flux->database_dest!=null)
                        <a href="{{ route('admin.databases.show',$flux->database_dest->id) }}">
                            {{ $flux->database_dest->name }}
                        </a>
                    @endif
                </td>
                <td>
                    @foreach($flux->informations as $info)
                        <a href="{{ route('admin.information.show',$info->id) }}">{{$info->name}}</a>
                        @if (!$loop->last) , @endif
                    @endforeach
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    </div>


    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $database->created_at ? $database->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $database->updated_at ? $database->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.databases.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>

@endsection
