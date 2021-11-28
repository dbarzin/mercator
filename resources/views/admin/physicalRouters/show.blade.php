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
                            {{ $physicalRouter->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.building') }}
                        </th>
                        <td>
                            {{ $physicalRouter->building->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.bay') }}
                        </th>
                        <td>
                            {{ $physicalRouter->bay->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalRouter.fields.vlan') }}
                        </th>
                        <td>
                            @foreach($physicalRouter->vlans as $key => $vlan)
                                <span class="label label-info">{{ $vlan->name }}</span>
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
</div>



@endsection