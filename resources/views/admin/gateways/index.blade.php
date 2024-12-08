@extends('layouts.admin')
@section('content')
@can('gateway_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.gateways.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.gateway.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.gateway.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.authentification') }}
                        </th>
                        <th>
                            {{ trans('cruds.gateway.fields.ip') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($gateways as $key => $gateway)
                        <tr data-entry-id="{{ $gateway->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.gateways.show', $gateway->id) }}">
                                {{ $gateway->name ?? '' }}
                            </a>
                            </td>
                            <td>
                                {!! $gateway->description ?? '' !!}
                            </td>
                            <td>
                                {{ $gateway->authentification ?? '' }}
                            </td>
                            <td>
                                {{ $gateway->ip ?? '' }}
                            </td>
                            <td nowrap>
                                @can('gateway_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.gateways.show', $gateway->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('gateway_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.gateways.edit', $gateway->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('gateway_delete')
                                    <form action="{{ route('admin.gateways.destroy', $gateway->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.gateway.title_singular"),
    'URL' => route('admin.gateways.massDestroy'),
    'canDelete' => auth()->user()->can('gateway_delete') ? true : false
));
</script>
@endsection
