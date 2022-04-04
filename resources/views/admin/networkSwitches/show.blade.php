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

                @can('entity_edit')
                    <a class="btn btn-info" href="{{ route('admin.network-switches.edit', $networkSwitch->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('entity_delete')
                    <form action="{{ route('admin.network-switches.destroy', $networkSwitch->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $networkSwitch->created_at ? $networkSwitch->created_at->format(trans('global.timestamp')) : '' }} |
        {{ trans('global.updated_at') }} {{ $networkSwitch->updated_at ? $networkSwitch->updated_at->format(trans('global.timestamp')) : '' }} 
    </div>
</div>
@endsection