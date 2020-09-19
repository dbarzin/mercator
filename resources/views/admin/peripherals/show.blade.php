@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.peripheral.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.peripherals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.peripheral.fields.id') }}
                        </th>
                        <td>
                            {{ $peripheral->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.peripheral.fields.name') }}
                        </th>
                        <td>
                            {{ $peripheral->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.peripheral.fields.type') }}
                        </th>
                        <td>
                            {{ $peripheral->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.peripheral.fields.description') }}
                        </th>
                        <td>
                            {!! $peripheral->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.peripheral.fields.responsible') }}
                        </th>
                        <td>
                            {{ $peripheral->responsible }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.peripheral.fields.site') }}
                        </th>
                        <td>
                            {{ $peripheral->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.peripheral.fields.building') }}
                        </th>
                        <td>
                            {{ $peripheral->building->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.peripheral.fields.bay') }}
                        </th>
                        <td>
                            {{ $peripheral->bay->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.peripherals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection