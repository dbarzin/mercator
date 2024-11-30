@extends('layouts.admin')
@section('content')
<div class="form-group">
    <a class="btn btn-default" href="{{ route('admin.networks.index') }}">
        {{ trans('global.back_to_list') }}
    </a>

    <a class="btn btn-success" href="{{ route('admin.report.explore') }}?node=NETWORK_{{$network->id}}">
        {{ trans('global.explore') }}
    </a>

    @can('network_edit')
        <a class="btn btn-info" href="{{ route('admin.networks.edit', $network->id) }}">
            {{ trans('global.edit') }}
        </a>
    @endcan

    @can('network_delete')
        <form action="{{ route('admin.networks.destroy', $network->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
        </form>
    @endcan
</div>
    <div class="card">
        <div class="card-header">
            {{ trans('global.show') }} {{ trans('cruds.network.title') }}
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th width="10%">
                            {{ trans('cruds.network.fields.name') }}
                        </th>
                        <td>
                            {{ $network->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.description') }}
                        </th>
                        <td>
                            {!! $network->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.protocol_type') }}
                        </th>
                        <td>
                            {{ $network->protocol_type }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.responsible') }}
                        </th>
                        <td>
                            {{ $network->responsible }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.responsible_sec') }}
                        </th>
                        <td>
                            {{ $network->responsible_sec }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.security_need') }}
                        </th>
                        <td>



                    <tr>
                        <th>
                            {{ trans('cruds.information.fields.security_need') }}
                            @if (config('mercator-config.parameters.security_need_auth'))
                            + {{ trans("global.authenticity_short") }}
                            @endif
                        </th>
                        <td>
                            {{ trans('global.confidentiality') }} :
                                @if ($network->security_need_c==0){{ trans('global.none') }}@endif
                                @if ($network->security_need_c==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                @if ($network->security_need_c==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                @if ($network->security_need_c==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                @if ($network->security_need_c==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                            &nbsp;
                            {{ trans('global.integrity') }} :
                                @if ($network->security_need_i==0){{ trans('global.none') }}@endif
                                @if ($network->security_need_i==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                @if ($network->security_need_i==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                @if ($network->security_need_i==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                @if ($network->security_need_i==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                            &nbsp;
                            {{ trans('global.availability') }} :
                                @if ($network->security_need_a==0){{ trans('global.none') }}@endif
                                @if ($network->security_need_a==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                @if ($network->security_need_a==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                @if ($network->security_need_a==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                @if ($network->security_need_a==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                            &nbsp;
                            {{ trans('global.tracability') }} :
                                @if ($network->security_need_t==0){{ trans('global.none') }}@endif
                                @if ($network->security_need_t==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                @if ($network->security_need_t==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                @if ($network->security_need_t==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                @if ($network->security_need_t==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                            @if (config('mercator-config.parameters.security_need_auth'))
                            &nbsp;
                            {{ trans('global.authenticity') }} :
                                @if ($network->security_need_auth==0){{ trans('global.none') }}@endif
                                @if ($network->security_need_auth==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                @if ($network->security_need_auth==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                @if ($network->security_need_auth==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                @if ($network->security_need_auth==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                            @endif
                            </td>
                        </tr>


                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.subnetworks') }}
                        </th>
                        <td>
                            @foreach($network->subnetworks as $subnetwork)
                                <a href="{{ route('admin.subnetworks.show', $subnetwork->id) }}">
                                    {{ $subnetwork->name }}
                                </a>
                                @if ($network->subnetworks->last()<>$subnetwork)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            {{ trans('global.created_at') }} {{ $network->created_at ? $network->created_at->format(trans('global.timestamp')) : '' }} |
            {{ trans('global.updated_at') }} {{ $network->updated_at ? $network->updated_at->format(trans('global.timestamp')) : '' }}
        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.networks.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>
@endsection
