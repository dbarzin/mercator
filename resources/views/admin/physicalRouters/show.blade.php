@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalRouter.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-routers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>

                <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=PROUTER_{{$physicalRouter->id}}">
                    {{ trans('global.explore') }}
                </a>

                @can('physical_router_edit')
                    <a class="btn btn-info" href="{{ route('admin.physical-routers.edit', $physicalRouter->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('physical_router_delete')
                    <form action="{{ route('admin.physical-routers.destroy', $physicalRouter->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
                    </form>
                @endcan

            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.physicalRouter.fields.name') }}
                        </th>
                        <td>
                            {{ $physicalRouter->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.description') }}
                        </th>
                        <td>
                            {!! $physicalRouter->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.type') }}
                        </th>
                        <td>
                            {{ $physicalRouter->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.site') }}
                        </th>
                        <td>
                            @if ($physicalRouter->site!=null)
                                <a href="{{ route('admin.sites.show', $physicalRouter->site->id) }}">
                                {{ $physicalRouter->site->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.building') }}
                        </th>
                        <td>
                            @if ($physicalRouter->building!=null)
                                <a href="{{ route('admin.buildings.show', $physicalRouter->building->id) }}">
                                {{ $physicalRouter->building->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.bay') }}
                        </th>
                        <td>
                            @if ($physicalRouter->bay!=null)
                                <a href="{{ route('admin.bays.show', $physicalRouter->bay->id) }}">
                                {{ $physicalRouter->bay->name ?? '' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.vlan') }}
                        </th>
                        <td>
                            @foreach($physicalRouter->vlans as $vlan)
                                <a href="{{ route('admin.vlans.show', $vlan->id) }}">
                                {{ $vlan->name }}
                                @if(!$loop->last)
                                ,
                                @endif                                
                                </a>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-routers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $physicalRouter->created_at ? $physicalRouter->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $physicalRouter->updated_at ? $physicalRouter->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>
@endsection