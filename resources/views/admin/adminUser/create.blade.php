@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.admin-users.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.adminUser.title_singular') }}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <div class="form-group">
                        <label class="label-required" for="userId">{{ trans('cruds.adminUser.fields.user_id') }}</label>
                        <input class="form-control {{ $errors->has('userId') ? 'is-invalid' : '' }}" type="text" name="user_id" id="user_id" value="{{ old('user_id', '') }}" maxlength="32" required autofocus/>
                        @if($errors->has('userId'))
                            <div class="invalid-feedback">
                                {{ $errors->first('userId') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.adminUser.fields.user_id_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="firstname">{{ trans('cruds.adminUser.fields.firstname') }}</label>
                        <input class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}" type="text" name="firstname" id="firstname" maxlength="64" value="{{ old('firstname', '') }}">
                        @if($errors->has('firstname'))
                            <div class="invalid-feedback">
                                {{ $errors->first('firstname') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.adminUser.fields.firstname_helper') }}</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="lastname">{{ trans('cruds.adminUser.fields.lastname') }}</label>
                        <input class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}" type="text" name="lastname" id="lastname" maxlength="64" value="{{ old('lastname', '') }}">
                        @if($errors->has('lastname'))
                            <div class="invalid-feedback">
                                {{ $errors->first('lastname') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.adminUser.fields.lastname_helper') }}</span>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="type">{{ trans('cruds.adminUser.fields.type') }}</label>
                        <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                            <option></option>
                            @foreach($type_list as $type)
                                <option {{ (old('type') === $type) ? 'selected' : '' }}>{{$type}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('type') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.adminUser.fields.type_helper') }}</span>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type">{{ trans('cruds.adminUser.fields.attributes') }}</label>
                        <select class="form-control select2-free {{ $errors->has('attributes') ? 'is-invalid' : '' }}" name="attributes[]" id="attributes" multiple>
                            @foreach($attributes_list as $a)
                                <option {{ str_contains(old('attributes'), $a) ? 'selected' : '' }}>{{$a}}</option>
                            @endforeach
                        </select>
                        <span class="help-block">{{ trans('cruds.adminUser.fields.attributes_helper') }}</span>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="domain">{{ trans('cruds.adminUser.fields.domain') }}</label>
                        <select class="form-control select2 {{ $errors->has('domain_id') ? 'is-invalid' : '' }}" name="domain_id" id="domain">
                            <option></option>
                            @foreach($domains as $id => $name)
                            <option value="{{ $id }}" {{ old('domain_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('domain_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('domain_id') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.adminUser.fields.domain_helper') }}</span>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label class="recommended" for="description">{{ trans('cruds.application.fields.description') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                        @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.adminUser.fields.description_helper') }}</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.admin-users.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button id="btn-save" class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection
