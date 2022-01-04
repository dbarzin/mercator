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
                @can('peripheral_edit')
                    <a class="btn btn-info" href="{{ route('admin.peripherals.edit', $peripheral->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('peripheral_delete')
                    <form action="{{ route('admin.peripherals.destroy', $peripheral->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $peripheral->created_at->format(trans('global.timestamp')) }} |
        {{ trans('global.updated_at') }} {{ $peripheral->updated_at->format(trans('global.timestamp')) }} 
    </div>
</div>
@endsection