@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.wifiTerminal.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.wifi-terminals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
                @can('wifi_terminal_edit')
                    <a class="btn btn-info" href="{{ route('admin.wifi-terminals.edit', $wifiTerminal->id) }}">
                        {{ trans('global.edit') }}
                    </a>
                @endcan

                @can('wifi_terminal_delete')
                    <form action="{{ route('admin.wifi-terminals.destroy', $wifiTerminal->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
                            {{ trans('cruds.wifiTerminal.fields.name') }}
                        </th>
                        <td>
                            {{ $wifiTerminal->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.description') }}
                        </th>
                        <td>
                            {!! $wifiTerminal->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.type') }}
                        </th>
                        <td>
                            {{ $wifiTerminal->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.site') }}
                        </th>
                        <td>
                            {{ $wifiTerminal->site->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.building') }}
                        </th>
                        <td>
                            {{ $wifiTerminal->building->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.wifi-terminals.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
    <div class="card-footer">
        {{ trans('global.created_at') }} {{ $wifiTerminal->created_at->format(trans('global.timestamp')) ?? '' }} |
        {{ trans('global.updated_at') }} {{ $wifiTerminal->updated_at->format(trans('global.timestamp')) ?? '' }} 
    </div>
</div>
@endsection