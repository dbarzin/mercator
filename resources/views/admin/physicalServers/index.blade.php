@extends('layouts.admin')
@section('content')
@can('physical_server_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route('admin.physical-servers.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.physicalServer.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.physicalServer.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.physicalServer.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalServer.fields.type') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalServer.fields.responsible') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalServer.fields.site') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalServer.fields.building') }}
                        </th>
                        <th>
                            {{ trans('cruds.physicalServer.fields.bay') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($physicalServers as $key => $physicalServer)
                        <tr data-entry-id="{{ $physicalServer->id }}"

                        @if (($physicalServer->description==null)||
                            ($physicalServer->configuration==null)||
                            ($physicalServer->site_id==null)||
                            ($physicalServer->building_id==null)||
                            ($physicalServer->responsible==null)
                            /* ($physicalServer->logicalServers()->count()==0) */
                            )
                                class="table-warning"
                        @endif

                            >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.physical-servers.show', $physicalServer->id) }}">
                                {{ $physicalServer->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $physicalServer->type ?? '' !!}
                            </td>
                            <td>
                                {{ $physicalServer->responsible }}
                            </td>
                            <td>
                                @if ($physicalServer->site!=null)
                                    <a href="{{ route('admin.sites.show', $physicalServer->site->id) }}">
                                        {{ $physicalServer->site->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                @if ($physicalServer->building!=null)
                                <a href="{{ route('admin.buildings.show', $physicalServer->building->id) }}">
                                    {{ $physicalServer->building->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td>
                                @if ($physicalServer->bay!=null)
                                <a href="{{ route('admin.bays.show', $physicalServer->bay->id) }}">
                                    {{ $physicalServer->bay->name ?? '' }}
                                </a>
                                @endif
                            </td>
                            <td nowrap>
                                @can('physical_server_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.physical-servers.show', $physicalServer->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('physical_server_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.physical-servers.edit', $physicalServer->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('physical_server_delete')
                                    <form action="{{ route('admin.physical-servers.destroy', $physicalServer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.physicalServer.title_singular"),
    'URL' => route('admin.physical-servers.massDestroy'),
    'canDelete' => auth()->user()->can('physical_server_delete') ? true : false
));
</script>
@endsection
