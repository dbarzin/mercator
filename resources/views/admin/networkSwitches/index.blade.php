@extends('layouts.admin')
@section('content')
@can('network_switch_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.network-switches.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.networkSwitch.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.networkSwitch.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.ip') }}
                        </th>
                        <th>
                            {{ trans('cruds.networkSwitch.fields.physical_switches') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($networkSwitches as $key => $networkSwitch)
                        <tr data-entry-id="{{ $networkSwitch->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.network-switches.show', $networkSwitch->id) }}">
                                    {{ $networkSwitch->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $networkSwitch->description ?? '' !!}
                            </td>
                            <td>
                                {{ $networkSwitch->ip ?? '' }}
                            </td>
                            <td>
                                @foreach($networkSwitch->physicalSwitches as $physicalSwitch)
                                    <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">
                                    {{ $physicalSwitch->name }}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('network_switch_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.network-switches.show', $networkSwitch->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('network_switch_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.network-switches.edit', $networkSwitch->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('network_switch_delete')
                                    <form action="{{ route('admin.network-switches.destroy', $networkSwitch->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.networkSwitch.title_singular"),
    'URL' => route('admin.network-switches.massDestroy'),
    'canDelete' => auth()->user()->can('network_switch_delete') ? true : false
));
</script>
@endsection
