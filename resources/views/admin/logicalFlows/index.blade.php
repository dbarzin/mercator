@extends('layouts.admin')
@section('content')
@can('logical_flow_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.logical-flows.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.logicalFlow.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.logicalFlow.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.router') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.priority') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.action') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.protocol') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.source_ip_range') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.source_port') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.dest_ip_range') }}
                        </th>
                        <th>
                            {{ trans('cruds.logicalFlow.fields.dest_port') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($logicalFlows as $logicalFlow)
                        <tr data-entry-id="{{ $logicalFlow->id }}">
                            <td>

                            </td>
                            <td>
                            <a href="{{ route('admin.logical-flows.show', $logicalFlow->id) }}">
                                {{ $logicalFlow->name ?? "NONAME" }}
                            </a>
                            </td>
                            <td>
                                @if ($logicalFlow->router_id !== null)
                                <a href="{{ route('admin.routers.show', $logicalFlow->router_id) }}">
                                    {{ $logicalFlow->router->name }}
                                </a>
                                @endif
                            </td>
                            <td>
                                {{ $logicalFlow->priority }}
                            </td>
                            <td>
                                {{ $logicalFlow->action }}
                            </td>
                            <td>
                                {!! $logicalFlow->description !!}
                            </td>
                            <td>
                                {{ $logicalFlow->protocol }}
                            </td>
                            <td>
                                {{ $logicalFlow->source_ip_range }}
                            </td>
                            <td>
                                {{ $logicalFlow->source_port ?? "ANY"  }}
                            </td>
                            <td>
                                {{ $logicalFlow->dest_ip_range }}
                            </td>
                            <td>
                                {{ $logicalFlow->dest_port ?? "ANY" }}
                            </td>
                            <td nowarp>
                                @can('logical_flow_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.logical-flows.show', $logicalFlow->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('logical_flow_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.logical-flows.edit', $logicalFlow->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('logical_flow_delete')
                                    <form action="{{ route('admin.logical-flows.destroy', $logicalFlow->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.logicalFlow.title_singular"),
    'URL' => route('admin.logical-flows.massDestroy'),
    'canDelete' => auth()->user()->can('logical_flow_delete') ? true : false
));
</script>
@endsection
