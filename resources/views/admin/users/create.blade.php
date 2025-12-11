@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.users.store") }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.user.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="label-required" for="name">{{ trans('cruds.user.fields.login') }}</label>
                    <input class="form-control {{ $errors->has('login') ? 'is-invalid' : '' }}" type="text" name="login"
                           id="login" value="{{ old('login', '') }}" required autofocus/>
                    @if($errors->has('login'))
                        <div class="invalid-feedback">
                            {{ $errors->first('login') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.login_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', '') }}" maxlength="128" required/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                    <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                           name="email" id="email" value="{{ old('email') }}" required>
                    @if($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
                </div>
                @if (Config::get('app.ldap_domain')==null)
                    <div class="form-group">
                        <label class="label-required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                        <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                               name="password" id="password" required>
                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
                    </div>
                @else
                    <input type="hidden" name="password" value="nopassword">
                @endif
                <div class="form-group">
                    <label class="label-required" for="title">{{ trans("cruds.user.fields.language") }}</label>
                    <select class="form-control select2 {{ $errors->has('language') ? 'is-invalid' : '' }}"
                            name="language" id="language">
                        <option value="en" {{ old("language") == 'en' ? 'selected' : '' }}>{{ trans("cruds.user.fields.language_en") }}</option>
                        <option value="fr" {{ old("language") == 'fr' ? 'selected' : '' }}>{{ trans("cruds.user.fields.language_fr") }}</option>
                        <option value="de" {{ old("language") == 'de' ? 'selected' : '' }}>{{ trans("cruds.user.fields.language_de") }}</option>
                    </select>
                    @if($errors->has('language'))
                        <div class="invalid-feedback">
                            {{ $errors->first('language') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.language_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]"
                            id="roles" multiple required>
                        @foreach($roles as $id => $roles)
                            <option value="{{ $id }}" {{ in_array($id, old('roles', [])) ? 'selected' : '' }}>{{ $roles }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('roles'))
                        <div class="invalid-feedback">
                            {{ $errors->first('roles') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-required" for="granularity">{{ trans('cruds.user.fields.granularity') }}</label>
                    <select class="form-control select2 {{ $errors->has('granularity') ? 'is-invalid' : '' }}"
                            name="granularity" id="granularity">
                        <option value="1" {{ old("granularity") == 1 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_1") }}</option>
                        <option value="2" {{ old("granularity") == 2 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_2") }}</option>
                        <option value="3" {{ old("granularity") == 3 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_3") }}</option>
                    </select>
                    @if($errors->has('granularity'))
                        <div class="invalid-feedback">
                            {{ $errors->first('granularity') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.user.fields.granularity_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.users.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
