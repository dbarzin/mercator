@extends('layouts.admin')
@section('content')
@can('network_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a id="btn-new" class="btn btn-success" href="{{ route('admin.networks.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.network.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.network.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.network.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.network.fields.description') }}
                        </th>
                        <th>
                            {{ trans('cruds.network.fields.protocol_type') }}
                        </th>
                        <th>
                            {{ trans('cruds.network.fields.security_need') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($networks as $key => $network)
                        <tr data-entry-id="{{ $network->id }}">
                            <td>

                            </td>
                            <td>
                                <a href="{{ route('admin.networks.show', $network->id) }}">
                                    {{ $network->name ?? '' }}
                                </a>
                            </td>
                            <td>
                                {!! $network->description ?? '' !!}
                            </td>
                            <td>
                                {!! $network->protocol_type ?? '' !!}
                            </td>
                            <td>
                                @if ($network->security_need_c==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($network->security_need_c==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($network->security_need_c==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($network->security_need_c==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif
                                -
                                @if ($network->security_need_i==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($network->security_need_i==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($network->security_need_i==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($network->security_need_i==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif
                                -
                                @if ($network->security_need_a==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($network->security_need_a==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($network->security_need_a==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($network->security_need_a==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif
                                -
                                @if ($network->security_need_t==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($network->security_need_t==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($network->security_need_t==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($network->security_need_t==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif
                                @if (config('mercator-config.parameters.security_need_auth'))
                                -
                                @if ($network->security_need_auth==1)
                                    <span class="veryLowRisk"> 1 </span>
                                @elseif ($network->security_need_auth==2)
                                    <span class="lowRisk"> 2 </span>
                                @elseif ($network->security_need_auth==3)
                                    <span class="mediumRisk"> 3 </span>
                                @elseif ($network->security_need_auth==4)
                                    <span class="highRisk"> 4 </span>
                                @else
                                    <span> * </span>
                                @endif
                                @endif
                            </td>
                            <td nowrap>
                                @can('network_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.networks.show', $network->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('network_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.networks.edit', $network->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('network_delete')
                                    <form action="{{ route('admin.networks.destroy', $network->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
    'title' => trans("cruds.network.title_singular"),
    'URL' => route('admin.networks.massDestroy'),
    'canDelete' => auth()->user()->can('network_delete') ? true : false
));
</script>
@endsection
