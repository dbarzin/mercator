@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.security-devices.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <!---------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.securityDevice.title_singular') }}
        </div>
        <!---------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.securityDevice.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required autofocus/>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.securityDevice.fields.name_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-group">
                        <label for="description">{{ trans('cruds.securityDevice.fields.description') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.securityDevice.fields.description_helper') }}</span>
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
                <div class="form-group">
                    <label for="logical_servers">{{ trans('cruds.securityDevice.fields.physical_security_devices') }}</label>
                    <select class="form-control select2 {{ $errors->has('physical_security_devices') ? 'is-invalid' : '' }}" name="physical_security_devices[]" id="physical_security_devices" multiple>
                        @foreach($physicalSecurityDevices as $id => $name)
                        <option value="{{ $id }}" {{ in_array($id, old('physical_security_devices', [])) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('logical_servers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('logical_servers') }}
                    </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.securityDevice.fields.physical_security_devices_helper') }}</span>
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
