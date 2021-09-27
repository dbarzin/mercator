@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.network.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.networks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
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
                            {{ trans('global.confidentiality') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$network->security_need_c] ?? "" }}
                            <br>
                            {{ trans('global.integrity') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$network->security_need_i] ?? "" }}
                            <br>
                            {{ trans('global.availability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$network->security_need_a] ?? "" }}
                            <br>
                            {{ trans('global.tracability') }} :
                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                [$network->security_need_t] ?? "" }}                                                        
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.network.fields.subnetworks') }}
                        </th>
                        <td>
                            @foreach($network->subnetworks as $key => $subnetworks)
                                <span class="label label-info">{{ $subnetworks->name }}</span>
                                @if ($network->subnetworks->last()<>$subnetworks)
                                ,
                                @endif
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.networks.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        {{ trans('global.relatedData') }}
    </div>
    <ul class="nav nav-tabs" role="tablist" id="relationship-tabs">
        <li class="nav-item">
            <a class="nav-link" href="#connected_networks_external_connected_entities" role="tab" data-toggle="tab">
                {{ trans('cruds.externalConnectedEntity.title') }}
            </a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane" role="tabpanel" id="connected_networks_external_connected_entities">
            @includeIf('admin.networks.relationships.externalConnectedEntities', ['externalConnectedEntities' => $network->externalConnectedEntities])
        </div>
    </div>
</div>

@endsection