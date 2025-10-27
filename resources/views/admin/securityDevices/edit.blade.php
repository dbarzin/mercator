@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.security-devices.update", [$securityDevice->id]) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.securityDevice.title_singular') }}
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.securityDevice.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="name" id="name" value="{{ old('name', $securityDevice->name) }}" required
                                   autofocus/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.securityDevice.fields.name_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-3">
                        <div class="form-group">
                            <label class="recommended"
                                   for="type">{{ trans('cruds.securityDevice.fields.type') }}</label>
                            <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}"
                                    name="type"
                                    id="type">
                                @if (!$type_list->contains(old('type')))
                                    <option> {{ old('type') }}</option>'
                                @endif
                                @foreach($type_list as $t)
                                    <option {{ (old('type') ? old('type') : $securityDevice->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('types'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('types') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.securityDevice.fields.type_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-5">
                        <div class="form-group">
                            <label for="attributes">{{ trans('cruds.securityDevice.fields.attributes') }}</label>
                            <select class="form-control select2-free {{ $errors->has('attributes') ? 'is-invalid' : '' }}"
                                    name="attributes[]" id="attributes[]" multiple>
                                @foreach($attributes_list as $a)
                                    <option {{ ( (old('attributes')!=null) && in_array($a,old('attributes'))) || in_array($a, explode(' ',$securityDevice->attributes)) ? 'selected' : '' }}>{{$a}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('attributes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('attributes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.securityDevice.fields.attributes_helper') }}</span>
                        </div>
                    </div>


                </div>
                <div class="row">
                    <div class="col-9">
                        <div class="form-group">
                            <label for="description">{{ trans('cruds.securityDevice.fields.description') }}</label>
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    name="description"
                                    id="description">{!! old('description', $securityDevice->description) !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.securityDevice.fields.description_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="iconSelect">{{ trans('global.icon_select') }}</label>
                            <select id="iconSelect"
                                    name="iconSelect"
                                    class="form-control js-icon-picker"
                                    data-icons='@json($icons)'
                                    data-selected="{{ $securityDevice->icon_id ?? '-1' }}"
                                    data-default-img="{{ asset('images/securitydevice.png') }}"
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
                {{ trans("cruds.menu.logical_infrastructure.title_short") }}
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="applications">{{ trans('cruds.securityDevice.fields.applications') }}</label>
                            <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}"
                                    name="applications[]" id="applications" multiple>
                                @foreach($applications as $id => $name)
                                    <option value="{{ $id }}" {{ (in_array($id, old('applications', [])) || $securityDevice->applications->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('applications'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('applications') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.securityDevice.fields.applications_helper') }}</span>
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
                    <div class="col-12">
                        <div class="form-group">
                            <label for="physical_security_devices">{{ trans('cruds.securityDevice.fields.physical_security_devices') }}</label>
                            <select class="form-control select2 {{ $errors->has('physical_security_devices') ? 'is-invalid' : '' }}"
                                    name="physical_security_devices[]" id="physical_security_devices" multiple>
                                @foreach($physicalSecurityDevices as $id => $name)
                                    <option value="{{ $id }}" {{ (in_array($id, old('physical_security_devices', [])) || $securityDevice->physicalSecurityDevices->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('physical_security_devices'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('physical_security_devices') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.securityDevice.fields.physical_security_devices_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.security-devices.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
