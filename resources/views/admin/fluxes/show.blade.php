@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.fluxes.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node={{$flux->source_id()}},{{$flux->dest_id()}}">
        {{ trans('global.explore') }}
    </a>

    @can('flux_edit')
        <a class="btn btn-info" href="{{ route('admin.fluxes.edit', $flux->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('flux_delete')
        <form action="{{ route('admin.fluxes.destroy', $flux->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.flux.title') }}
    </div>

    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.flux.fields.name') }}
                    </th>
                    <td>
                        {{ $flux->name }}
                    </td>
                </tr>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.flux.fields.nature') }}
                    </th>
                    <td>
                        {{ $flux->nature }}
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.flux.fields.description') }}
                    </th>
                    <td>
                        {!! $flux->description !!}
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.flux.fields.source') }}
                    </th>
                    <td>
                        @if ($flux->application_source!=null)
                            <a href="{{ route('admin.applications.show',$flux->application_source->id) }}">
                            {{ $flux->application_source->name }}
                            </a>
                        @endif
                        @if (auth()->user()->granularity>=2)
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
                        @endif
                        @if ($flux->database_source!=null)
                            <a href="{{ route('admin.databases.show',$flux->database_source->id) }}">
                            {{ $flux->database_source->name }}
                            </a>
                        @endif
                    </td>
                </tr>

                <tr>
                    <th>
                        {{ trans('cruds.flux.fields.destination') }}
                    </th>
                    <td>
                        @if ($flux->application_dest!=null)
                            <a href="{{ route('admin.applications.show',$flux->application_dest->id) }}">
                            {{ $flux->application_dest->name }}
                            </a>
                        @endif
                        @if (auth()->user()->granularity>=2)
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
                        @endif
                        @if ($flux->database_dest!=null)
                            <a href="{{ route('admin.databases.show',$flux->database_dest->id) }}">
                            {{ $flux->database_dest->name }}
                            </a>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.flux.fields.crypted') }}
                    </th>
                    <td>
                        @if ($flux->crypted==0)
                            Non
                        @elseif ($flux->crypted==1)
                            Oui
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>
                        {{ trans('cruds.flux.fields.bidirectional') }}
                    </th>
                    <td>
                        @if ($flux->bidirectional==0)
                            Non
                        @elseif ($flux->bidirectional==1)
                            Oui
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $flux->created_at ? $flux->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $flux->updated_at ? $flux->updated_at->format(trans('global.timestamp')) : '' }}
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.fluxes.index') }}">
        {{ trans('global.back_to_list') }}
    </a>
</div>
@endsection
