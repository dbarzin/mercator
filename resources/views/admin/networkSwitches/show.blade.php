@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.networkSwitch.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.network-switches.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.networkSwitch.fields.name') }}
                        </th>
                        <td>
                            {{ $networkSwitch->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.description') }}
                        </th>
                        <td>
                            {!! $networkSwitch->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.ip') }}
                        </th>
                        <td>
                            {{ $networkSwitch->ip }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.network-switches.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection