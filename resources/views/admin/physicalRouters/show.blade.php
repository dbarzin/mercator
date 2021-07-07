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