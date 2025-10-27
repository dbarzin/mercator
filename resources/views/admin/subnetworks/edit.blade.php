@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.subnetworks.update", [$subnetwork->id]) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.subnetwork.title_singular') }}
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.subnetwork.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', $subnetwork->name) }}" required autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.name_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended"
                           for="description">{{ trans('cruds.subnetwork.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description"
                              id="description">{!! old('description', $subnetwork->description) !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.description_helper') }}</span>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <table width='100%'>
                            <tr>
                                <td>
                                    <div class="form-group">
                                        <label class="recommended"
                                               for="address">{{ trans('cruds.subnetwork.fields.address') }}</label>
                                        <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                               type="text" name="address" id="address"
                                               value="{{ old('address', $subnetwork->address) }}">
                                        @if($errors->has('address'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('address') }}
                                            </div>
                                        @endif
                                        <span class="help-block">{{ trans('cruds.subnetwork.fields.address_helper') }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <label class="recommended"
                                               for="default_gateway">{{ trans('cruds.subnetwork.fields.default_gateway') }}</label>
                                        <input class="form-control {{ $errors->has('default_gateway') ? 'is-invalid' : '' }}"
                                               type="text" name="default_gateway" id="default_gateway"
                                               value="{{ old('default_gateway', $subnetwork->default_gateway) }}">
                                        @if($errors->has('default_gateway'))
                                            <div class="invalid-feedback">
                                                {{ $errors->first('default_gateway') }}
                                            </div>
                                        @endif
                                        <span class="help-block">{{ trans('cruds.subnetwork.fields.default_gateway_helper') }}</span>
                                    </div>
                                </td>
                            </tr>
                        </table>

                        <div class="form-group">
                            <label class="recommended" for="vlan_id">{{ trans('cruds.subnetwork.fields.vlan') }}</label>
                            <select class="form-control select2 {{ $errors->has('vlan') ? 'is-invalid' : '' }}"
                                    name="vlan_id" id="vlan_id">
                                @foreach($vlans as $id => $vlan)
                                    <option value="{{ $id }}" {{ ($subnetwork->vlan ? $subnetwork->vlan->id : old('vlan_id')) == $id ? 'selected' : '' }}>{{ $vlan }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('vlan'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('vlan') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subnetwork.fields.vlan_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="recommended"
                                   for="ip_allocation_type">{{ trans('cruds.subnetwork.fields.ip_allocation_type') }}</label>
                            <select class="form-control select2-free {{ $errors->has('ip_allocation_type') ? 'is-invalid' : '' }}"
                                    name="ip_allocation_type" id="ip_allocation_type">
                                @if (!$ip_allocation_type_list->contains(old('ip_allocation_type')))
                                    <option> {{ old('ip_allocation_type') }}</option>
                                @endif
                                @foreach($ip_allocation_type_list as $t)
                                    <option {{ (old('ip_allocation_type') ? old('ip_allocation_type') : $subnetwork->ip_allocation_type) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('ip_allocation_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('ip_allocation_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subnetwork.fields.ip_allocation_type_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label for="gateway_id">{{ trans('cruds.subnetwork.fields.gateway') }}</label>
                            <select class="form-control select2 {{ $errors->has('gateway') ? 'is-invalid' : '' }}"
                                    name="gateway_id" id="gateway_id">
                                @foreach($gateways as $id => $gateway)
                                    <option value="{{ $id }}" {{ ($subnetwork->gateway ? $subnetwork->gateway->id : old('gateway_id')) == $id ? 'selected' : '' }}>{{ $gateway }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('gateway'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('gateway') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subnetwork.fields.gateway_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-sm">

                        <div class="form-group">
                            <label for="zone">{{ trans('cruds.subnetwork.fields.zone') }}</label>
                            <select class="form-control select2-free {{ $errors->has('zone') ? 'is-invalid' : '' }}"
                                    name="zone" id="zone">
                                @if (!$zone_list->contains(old('zone')))
                                    <option> {{ old('zone') }}</option>
                                @endif
                                @foreach($zone_list as $z)
                                    <option {{ (old('zone') ? old('zone') : $subnetwork->zone) == $z ? 'selected' : '' }}>{{$z}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('zone'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('zone') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subnetwork.fields.zone_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="recommended" for="dmz">{{ trans('cruds.subnetwork.fields.dmz') }}</label>
                            <select class="form-control select2-free {{ $errors->has('dmz') ? 'is-invalid' : '' }}"
                                    name="dmz" id="dmz">
                                @if (!$dmz_list->contains(old('dmz')))
                                    <option> {{ old('dmz') }}</option>
                                @endif
                                @foreach($dmz_list as $z)
                                    <option {{ (old('dmz') ? old('dmz') : $subnetwork->dmz) == $z ? 'selected' : '' }}>{{$z}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('dmz'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dmz') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subnetwork.fields.dmz_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="recommended" for="wifi">{{ trans('cruds.subnetwork.fields.wifi') }}</label>
                            <select class="form-control select2-free {{ $errors->has('wifi') ? 'is-invalid' : '' }}"
                                    name="wifi" id="wifi">
                                @if (!$wifi_list->contains(old('wifi')))
                                    <option> {{ old('wifi') }}</option>
                                @endif
                                @foreach($wifi_list as $w)
                                    <option {{ (old('wifi') ? old('wifi') : $subnetwork->wifi) == $w ? 'selected' : '' }}>{{$w}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wifi'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('wifi') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subnetwork.fields.wifi_helper') }}</span>
                        </div>

                        <div class="form-group">
                            <label class="recommended"
                                   for="responsible_exp">{{ trans('cruds.subnetwork.fields.responsible_exp') }}</label>
                            <select class="form-control select2-free {{ $errors->has('responsible_exp') ? 'is-invalid' : '' }}"
                                    name="responsible_exp" id="responsible_exp">
                                @if (!$responsible_exp_list->contains(old('responsible_exp')))
                                    <option> {{ old('responsible_exp') }}</option>
                                @endif
                                @foreach($responsible_exp_list as $t)
                                    <option {{ (old('responsible_exp') ? old('responsible_exp') : $subnetwork->responsible_exp) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('responsible'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('responsible_exp') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.subnetwork.fields.responsible_exp_helper') }}</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="vlan_id">{{ trans('cruds.subnetwork.fields.network') }}</label>
                    <select class="form-control select2 {{ $errors->has('network') ? 'is-invalid' : '' }}"
                            name="network_id" id="network_id">
                        @foreach($networks as $id => $network)
                            <option value="{{ $id }}" {{ ($subnetwork->network ? $subnetwork->network->id : old('network_id')) == $id ? 'selected' : '' }}>{{ $network }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('network'))
                        <div class="invalid-feedback">
                            {{ $errors->first('network') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.network_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.subnetworks.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
