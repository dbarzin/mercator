@extends('layouts.admin')
@section('content')
@can('wifi_terminal_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route("admin.wifi-terminals.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.wifiTerminal.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.wifiTerminal.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.address_ip') }}
                        </th>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.wifiTerminal.fields.building') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($wifiTerminals as $key => $wifiTerminal)
                        <tr data-entry-id="{{ $wifiTerminal->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.wifi-terminals.show', $wifiTerminal->id) }}">
                                {{ $wifiTerminal->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {{ $wifiTerminal->type ?? '' }}
                            </td>
                            <td>
                                {{ $wifiTerminal->address_ip }}
                            </td>
                            <td>
                                @if ($wifiTerminal->site!==null)
                                    <a href="{{ route('admin.sites.show', $wifiTerminal->site_id) }}">{{ $wifiTerminal->site->name }}</a>
                                @endif
                            </td>
                            <td>
                                @if ($wifiTerminal->building!==null)
                                    <a href="{{ route('admin.buildings.show', $wifiTerminal->building_id) }}">{{ $wifiTerminal->building->name }}</a>
                                @endif
                            </td>
                            <td nowrap>
                                @can('wifi_terminal_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.wifi-terminals.show', $wifiTerminal->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('wifi_terminal_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.wifi-terminals.edit', $wifiTerminal->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('wifi_terminal_delete')
                                    <form action="{{ route('admin.wifi-terminals.destroy', $wifiTerminal->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.wifiTerminal.title_singular"),
    'URL' => route('admin.wifi-terminals.massDestroy'),
    'canDelete' => auth()->user()->can('wifi_terminal_delete') ? true : false
));
</script>
@endsection
