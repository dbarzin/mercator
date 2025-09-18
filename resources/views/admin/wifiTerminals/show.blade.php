@extends('layouts.admin')
@section('content')

    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.wifi-terminals.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=WIFI_{{$wifiTerminal->id}}">
            {{ trans('global.explore') }}
        </a>

        @can('wifi_terminal_edit')
            <a class="btn btn-info" href="{{ route('admin.wifi-terminals.edit', $wifiTerminal->id) }}">
                {{ trans('global.edit') }}
            </a>
        @endcan

        @can('vlan_create')
            <a class="btn btn-warning" href="{{ route('admin.wifi-terminals.clone', $wifiTerminal->id) }}">
                {{ trans('global.clone') }}
            </a>
        @endcan

        @can('wifi_terminal_delete')
            <form action="{{ route('admin.wifi-terminals.destroy', $wifiTerminal->id) }}" method="POST"
                  onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                <input type="hidden" name="_method" value="DELETE">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
            </form>
        @endcan

    </div>

    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.wifiTerminal.title') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.wifiTerminal.fields.name') }}
                        </th>
                        <td>
                            {{ $wifiTerminal->name }}
                        </td>
                        <th width="10%">
                            {{ trans('cruds.wifiTerminal.fields.type') }}
                        </th>
                        <td>
                            {{ $wifiTerminal->type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.description') }}
                        </th>
                        <td colspan="3">
                            {!! $wifiTerminal->description !!}
                        </td>
                    </tr>
                    <tr>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.address_ip') }}
                        </th>
                        <td colspan="3">
                            {{ $wifiTerminal->address_ip ?? '' }}
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            Common Platform Enumeration (CPE)
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                <tr>
                    <th width="10%">
                        {{ trans('cruds.application.fields.vendor') }}
                    </th>
                    <td width="22%">
                        {{ $wifiTerminal->vendor }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.application.fields.product') }}
                    </th>
                    <td width="22%">
                        {{ $wifiTerminal->product }}
                    </td>
                    <th width="10%">
                        {{ trans('cruds.application.fields.version') }}
                    </th>
                    <td width="22%">
                        {{ $wifiTerminal->version }}
                    </td>
                    <td>
                        <form action="{{ route('admin.cve.search','cpe:2.3:a:'. $wifiTerminal->vendor.':'. $wifiTerminal->product . ':' . $wifiTerminal->version) }}"
                              method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                            <input type="submit" class="btn btn-info"
                                   value="{{ trans('global.search') }}" {{ (($wifiTerminal->vendor==null)||($wifiTerminal->product==null)) ? 'disabled' : '' }} />
                        </form>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans("cruds.menu.physical_infrastructure.title_short") }}
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">

            <div class="form-group">
                <table class="table table-bordered table-striped">
                    <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.wifiTerminal.fields.site') }}
                        </th>
                        <td width="30%">
                            @if ($wifiTerminal->site!==null)
                                <a href="{{ route('admin.sites.show', $wifiTerminal->site_id) }}">{{ $wifiTerminal->site->name }}</a>
                            @endif
                        </td>
                        <th width="10%">
                            {{ trans('cruds.wifiTerminal.fields.building') }}
                        </th>
                        <td width="40%">
                            @if ($wifiTerminal->building!==null)
                                <a href="{{ route('admin.buildings.show', $wifiTerminal->building_id) }}">{{ $wifiTerminal->building->name }}</a>
                            @endif
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $wifiTerminal->created_at ? $wifiTerminal->created_at->format(trans('global.timestamp')) : '' }}
            |
            {{ trans('global.updated_at') }} {{ $wifiTerminal->updated_at ? $wifiTerminal->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.wifi-terminals.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
