@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.physicalSwitch.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-switches.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.id') }}
                        </th>
                        <td>
                            {{ $physicalSwitch->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.name') }}
                        </th>
                        <td>
                            {{ $physicalSwitch->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.description') }}
                        </th>
                        <td>
                            {!! $physicalSwitch->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.type') }}
                        </th>
                        <td>
                            {{ $physicalSwitch->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.site') }}
                        </th>
                        <td>
                            {{ $physicalSwitch->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.building') }}
                        </th>
                        <td>
                            {{ $physicalSwitch->building->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.bay') }}
                        </th>
                        <td>
                            {{ $physicalSwitch->bay->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.physical-switches.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection