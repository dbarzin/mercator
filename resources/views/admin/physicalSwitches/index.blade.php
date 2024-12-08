@extends('layouts.admin')
@section('content')
@can('physical_switch_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.physical-switches.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.physicalSwitch.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.physicalSwitch.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.building') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.bay') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalSwitch.fields.network_switches') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($physicalSwitches as $key => $physicalSwitch)
                        <tr data-entry-id="{{ $physicalSwitch->id }}"
                        @if (
                            ($physicalSwitch->description==null)||
                            ($physicalSwitch->type==null)||
                            ($physicalSwitch->site==null)||
                            ($physicalSwitch->building==null)||
                            ($physicalSwitch->bay==null)
                            )
                                class="table-warning"
                        @endif
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">
                                    {{ $physicalSwitch->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $physicalSwitch->description ?? '' !!}
                            </td>
                            <td>
                                {!! $physicalSwitch->type ?? '' !!}
                            </td>
                            <td>
                                @if($physicalSwitch->site!=null)
                                <a href="{{ route('admin.sites.show', $physicalSwitch->site->id) }}">
                                    {{ $physicalSwitch->site->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td>
                                @if($physicalSwitch->building!=null)
                                <a href="{{ route('admin.buildings.show', $physicalSwitch->building->id) }}">
                                    {{ $physicalSwitch->building->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td>
                                @if($physicalSwitch->bay!=null)
                                <a href="{{ route('admin.bays.show', $physicalSwitch->bay->id) }}">
                                    {{ $physicalSwitch->bay->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td>
                                @foreach($physicalSwitch->networkSwitches as $networkSwitch)
                                    <a href="{{ route('admin.network-switches.show', $networkSwitch->id) }}">
                                    {{ $networkSwitch->name }}
                                    </a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                            </td>
                            <td nowrap>
                                @can('physical_switch_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.physical-switches.show', $physicalSwitch->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('physical_switch_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.physical-switches.edit', $physicalSwitch->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('physical_switch_delete')
                                    <form action="{{ route('admin.physical-switches.destroy', $physicalSwitch->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.physicalSwitch.title_singular"),
    'URL' => route('admin.physical-switches.massDestroy'),
    'canDelete' => auth()->user()->can('physical_switch_delete') ? true : false
));
</script>
@endsection
