@extends('layouts.admin')
@section('content')
@can('subnetwork_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route('admin.subnetworks.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.subnetwork.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.subnetwork.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class=" table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.subnetwork.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.subnetwork.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.subnetwork.fields.address') }}
                        </th>
                        <th>
                            {{ trans('cruds.subnetwork.fields.ip_range') }}
                        </th>
                        <th>
                            {{ trans('cruds.subnetwork.fields.default_gateway') }}
                        </th>
                        <th>
                            {{ trans('cruds.subnetwork.fields.vlan') }}
                        </th>
                        <th>
                            {{ trans('cruds.subnetwork.fields.zone') }}
                        </th>
                        <th>
                            {{ trans('cruds.subnetwork.fields.network') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($subnetworks as $key => $subnetwork)
                        <tr data-entry-id="{{ $subnetwork->id }}"
                          @if(
                            ($subnetwork->description==null)||
                            ($subnetwork->address==null)||
                            ($subnetwork->default_gateway==null)||
                            ($subnetwork->ip_allocation_type==null)||
                            ($subnetwork->vlan_id==null)||
                            ($subnetwork->responsible_exp==null)||
                            ($subnetwork->wifi==null)
                            )
                          class="table-warning"
                          @endif
                          >
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.subnetworks.show', $subnetwork->id) }}">
                                {{ $subnetwork->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $subnetwork->description ?? '' !!}
                            </td>
                            <td>
                                {{ $subnetwork->address ?? '' }}
                            </td>
                            <td>
                                {{ $subnetwork->ipRange() }}
                            </td>
                            <td>
                                {{ $subnetwork->default_gateway }}
                            </td>
                            <td>
                                @if ($subnetwork->vlan!=null)
                                    <a href="{{ route('admin.vlans.show', $subnetwork->vlan->id) }}">
                                        {{ $subnetwork->vlan->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $subnetwork->zone }}
                            </td>
                            <td>
                                @if ($subnetwork->network!=null)
                                    <a href="{{ route('admin.networks.show', $subnetwork->network->id) }}">
                                        {{ $subnetwork->network->name ?? '' }}
                                    </a>
                                @endif
                            </td>
                            <td nowrap>
                                @can('subnetwork_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.subnetworks.show', $subnetwork->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('subnetwork_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.subnetworks.edit', $subnetwork->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('subnetwork_delete')
                                    <form action="{{ route('admin.subnetworks.destroy', $subnetwork->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.subnetwork.title_singular"),
    'URL' => route('admin.subnetworks.massDestroy'),
    'canDelete' => auth()->user()->can('subnetwork_delete') ? true : false
));
</script>
@endsection
