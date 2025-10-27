@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.workstations.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.workstation.title_singular') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.workstation.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="name" id="name" value="{{ old('name', '') }}" required autofocus/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.name_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="type">{{ trans('cruds.workstation.fields.type') }}</label>
                            <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}"
                                    name="type" id="type">
                                @if (!$type_list->contains(old('type')))
                                    <option> {{ old('type') }}</option>
                                @endif
                                @foreach($type_list as $t)
                                    <option {{ old('type') == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.type_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label for="type">{{ trans('cruds.workstation.fields.status') }}</label>
                            <select class="form-control select2-free {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                    name="status" id="status">
                                @if (!$type_list->contains(old('status')))
                                    <option> {{ old('status') }}</option>
                                @endif
                                @foreach($status_list as $status)
                                    <option {{ old('status') == $status ? 'selected' : '' }}>{{$status}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('status'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('status') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.status_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.workstation.fields.description') }}</label>
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    name="description" id="description">{!! old('description') !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.description_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="iconSelect">{{ trans('global.icon_select') }}</label>
                            <select id="iconSelect"
                                    name="iconSelect"
                                    class="form-control js-icon-picker"
                                    data-icons='@json($icons)'
                                    data-selected="-1"
                                    data-default-img="{{ asset('images/workstation.png') }}"
                                    data-url-template="{{ route('admin.documents.show', ':id') }}"
                                    data-upload="#iconFile">
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="file" id="iconFile" name="iconFile" accept="image/png"/>
                        </div>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-header">
                Model / Configuration
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <label for="manufacturer">{{ trans('cruds.workstation.fields.manufacturer') }}</label>
                        <select class="form-control select2-free {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                name="manufacturer" id="manufacturer">
                            @if (!$type_list->contains(old('manufacturer')))
                                <option> {{ old('manufacturer') }}</option>
                            @endif
                            @foreach($manufacturer_list as $manufacturer)
                                <option {{ old('manufacturer') == $manufacturer ? 'selected' : '' }}>{{$manufacturer}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('manufacturer'))
                            <div class="invalid-feedback">
                                {{ $errors->first('manufacturer') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.manufacturer_helper') }}</span>
                    </div>
                    <div class="col-md-4">
                        <label for="model">{{ trans('cruds.workstation.fields.model') }}</label>
                        <select class="form-control select2-free {{ $errors->has('status') ? 'is-invalid' : '' }}"
                                name="model" id="model">
                            @if (!$model_list->contains(old('model')))
                                <option> {{ old('model') }}</option>
                            @endif
                            @foreach($model_list as $model)
                                <option {{ old('model') == $model ? 'selected' : '' }}>{{$model}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('model'))
                            <div class="invalid-feedback">
                                {{ $errors->first('model') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.model_helper') }}</span>
                    </div>
                    <div class="col-md-4">
                        <label for="serial_number">{{ trans('cruds.workstation.fields.serial_number') }}</label>
                        <input class="form-control {{ $errors->has('serial_number') ? 'is-invalid' : '' }}" type="text"
                               name="serial_number" id="serial_number" value="{{ old('serial_number', '') }}"/>
                        @if($errors->has('serial_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('serial_number') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.serial_number_helper') }}</span>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="cpu">{{ trans('cruds.workstation.fields.cpu') }}</label>
                            <select class="form-control select2-free {{ $errors->has('cpu') ? 'is-invalid' : '' }}"
                                    name="cpu" id="cpu">
                                @if (!$type_list->contains(old('cpu')))
                                    <option> {{ old('cpu') }}</option>
                                @endif
                                @foreach($cpu_list as $c)
                                    <option {{ old('cpu') == $c ? 'selected' : '' }}>{{$c}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('cpu'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cpu') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.cpu_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="memory">{{ trans('cruds.workstation.fields.memory') }}</label>
                            <input class="form-control {{ $errors->has('memory') ? 'is-invalid' : '' }}" type="text"
                                   name="memory" id="memory" value="{{ old('memory', '') }}">
                            @if($errors->has('memory'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('memory') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.memory_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="disk">{{ trans('cruds.workstation.fields.disk') }}</label>
                            <input class="form-control {{ $errors->has('disk') ? 'is-invalid' : '' }}" type="text"
                                   name="disk" id="disk" value="{{ old('disk', '') }}">
                            @if($errors->has('disk'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('disk') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.disk_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.administration.title_short") }}
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="entity_id">{{ trans('cruds.workstation.fields.entity') }}</label>
                            <select class="form-control select2 {{ $errors->has('entity') ? 'is-invalid' : '' }}"
                                    name="entity_id" id="entity_id">
                                @foreach($entities as $id => $name)
                                    <option value="{{ $id }}" {{ old('$entity_id') == $id ? 'selected' : '' }}>{{$name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('entity_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entity_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.entity_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="domain_id">{{ trans('cruds.workstation.fields.domain') }}</label>
                            <select class="form-control select2 {{ $errors->has('domain') ? 'is-invalid' : '' }}"
                                    name="domain_id" id="domain_id">
                                @foreach($domains as $id => $name)
                                    <option value="{{ $id }}" {{ old('$domain_id') == $id ? 'selected' : '' }}>{{$name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('domain_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('domain_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.domain_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="domain_id">{{ trans('cruds.workstation.fields.user') }}</label>
                            <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}"
                                    name="user_id" id="user_id">
                                @foreach($users as $id => $user_id)
                                    <option value="{{ $id }}" {{ old('$domain_id') == $id ? 'selected' : '' }}>{{$user_id}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('user_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('user_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.user_id_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="domain_id">{{ trans('cruds.workstation.fields.other_user') }}</label>
                            <input class="form-control {{ $errors->has('user') ? 'is-invalid' : '' }}" type="text"
                                   name="other_user" id="other_user" value="{{ old('other_user', '') }}">
                            @if($errors->has('other_user'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('other_user') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.other_user_helper') }}</span>
                        </div>
                    </div>

                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.application.title_short") }}
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="operating_system">{{ trans('cruds.workstation.fields.operating_system') }}</label>
                            <select class="form-control select2-free {{ $errors->has('operating_system') ? 'is-invalid' : '' }}"
                                    name="operating_system" id="operating_system">
                                @if (!$type_list->contains(old('operating_system')))
                                    <option> {{ old('operating_system') }}</option>
                                @endif
                                @foreach($operating_system_list as $os)
                                    <option {{ old('operating_system')  == $os ? 'selected' : '' }}>{{$os}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('operating_system'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('operating_system') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.operating_system_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg">
                        <div class="form-group">
                            <label for="applications">{{ trans('cruds.workstation.fields.applications') }}</label>
                            <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}"
                                    name="applications[]" id="applications" multiple>
                                @foreach($application_list as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('applications', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('applications'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('applications') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.applications_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.logical_infrastructure.title_short") }}
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="address_ip">{{ trans('cruds.workstation.fields.network') }}</label>
                            <select class="form-control select2 {{ $errors->has('domain') ? 'is-invalid' : '' }}"
                                    name="network_id" id="network_id">
                                @foreach($networks as $id => $name)
                                    <option value="{{ $id }}" {{ old('$network_id') == $id ? 'selected' : '' }}>{{$name}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('network_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('network_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.network_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="address_ip">{{ trans('cruds.workstation.fields.address_ip') }}</label>
                            <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text"
                                   name="address_ip" id="address_ip" value="{{ old('address_ip', '') }}">
                            @if($errors->has('address_ip'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('address_ip') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.address_ip_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="address_ip">{{ trans('cruds.workstation.fields.mac_address') }}</label>
                            <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text"
                                   name="mac_address" id="mac_address" value="{{ old('mac_address', '') }}">
                            @if($errors->has('mac_address'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('mac_address') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.mac_address_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="operating_system">{{ trans('cruds.workstation.fields.network_port_type') }}</label>
                            <select class="form-control select2-free {{ $errors->has('operating_system') ? 'is-invalid' : '' }}"
                                    name="network_port_type" id="network_port_type">
                                @if (!$type_list->contains(old('network_port_type')))
                                    <option> {{ old('network_port_type') }}</option>
                                @endif
                                @foreach($network_port_type_list as $type)
                                    <option {{ old('network_port_type')  == $type ? 'selected' : '' }}>{{$type}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('network_port_type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('network_port_type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.network_port_type_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.physical_infrastructure.title_short") }}
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="site_id">{{ trans('cruds.workstation.fields.site') }}</label>
                            <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}"
                                    name="site_id" id="site_id">
                                @foreach($sites as $id => $site)
                                    <option value="{{ $id }}" {{ old('site_id') == $id ? 'selected' : '' }}>{{ $site }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('site'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('site') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.site_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="building_id">{{ trans('cruds.workstation.fields.building') }}</label>
                            <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}"
                                    name="building_id" id="building_id">
                                @foreach($buildings as $id => $building)
                                    <option value="{{ $id }}" {{ old('building_id') == $id ? 'selected' : '' }}>{{ $building }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('building'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('building') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.workstation.fields.building_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.workstations.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection

