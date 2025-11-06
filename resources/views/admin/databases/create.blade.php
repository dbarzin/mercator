@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.databases.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.database.title_singular') }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-8">

                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.database.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="name" id="name" value="{{ old('name', '') }}" required autofocus/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.name_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="recommended1" for="type">{{ trans('cruds.database.fields.type') }}</label>
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
                            <span class="help-block">{{ trans('cruds.database.fields.type_helper') }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
                            <label class="recommended1"
                                   for="description">{{ trans('cruds.database.fields.description') }}</label>
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    name="description" id="description">{!! old('description') !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.description_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.ecosystem.title_short") }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label for="entities">{{ trans('cruds.database.fields.entities') }}</label>
                            <select class="form-control select2 {{ $errors->has('entities') ? 'is-invalid' : '' }}"
                                    name="entities[]" id="entities" multiple>
                                @foreach($entities as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('entities', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('entities'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entities') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.entities_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="recommended1"
                                   for="entity_resp_id">{{ trans('cruds.database.fields.entity_resp') }}</label>
                            <select class="form-control select2 {{ $errors->has('entity_resp') ? 'is-invalid' : '' }}"
                                    name="entity_resp_id" id="entity_resp_id">
                                @foreach($entity_resps as $id => $entity_resp)
                                    <option value="{{ $id }}" {{ old('entity_resp_id') == $id ? 'selected' : '' }}>{{ $entity_resp }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('entity_resp_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entity_resp_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.entity_resp_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-sm-4">
                        <div class="form-group">
                            <label class="recommended1"
                                   for="responsible">{{ trans('cruds.database.fields.responsible') }}</label>
                            <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}"
                                    name="responsible" id="responsible">
                                @if (!$responsible_list->contains(old('responsible')))
                                    <option> {{ old('responsible') }}</option>
                                @endif
                                @foreach($responsible_list as $t)
                                    <option {{ old('responsible') == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('responsible'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('responsible') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.responsible_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.metier.title_short") }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">

                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="informations">{{ trans('cruds.database.fields.informations') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('informations') ? 'is-invalid' : '' }}"
                                    name="informations[]" id="informations" multiple>
                                @foreach($informations as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('informations', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('informations'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('informations') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.informations_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.application.title") }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="applications">{{ trans('cruds.database.fields.applications') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}"
                                    name="applications[]" id="applications" multiple>
                                @foreach($applications as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('applications', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('applications'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('applications') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.applications_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.logical_infrastructure.title_short") }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="logical_servers">{{ trans('cruds.database.fields.logical_servers') }}</label>
                            <select class="form-control select2 {{ $errors->has('logical_servers') ? 'is-invalid' : '' }}"
                                    name="logical_servers[]" id="logical_servers" multiple>
                                @foreach($logical_servers as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('logical_servers', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('logical_servers'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('logical_servers') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.logical_servers_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="external">{{ trans('cruds.database.fields.external') }}</label>
                            <select class="form-control select2-free {{ $errors->has('external') ? 'is-invalid' : '' }}"
                                    name="external" id="external">
                                @if (!$external_list->contains(old('external')))
                                    <option> {{ old('external') }}</option>
                                @endif
                                @foreach($external_list as $t)
                                    <option {{ old('external') == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('external'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('external') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.database.fields.external_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans('cruds.database.title_security') }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">

                <div class="form-group">
                    <table cellspacing="5" cellpadding="5" border="0">
                        <tr>
                            <td width='20%'>
                                <label class="recommended2"
                                       for="security_need">{{ trans('cruds.database.fields.security_need') }}</label>
                            </td>
                            <td align="right" width="10">
                                <label for="security_need">{{ trans('global.confidentiality_short') }}</label>
                            </td>
                            <td width="120">
                                <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}"
                                        name="security_need_c" id="security_need_c">
                                    <option value="-1" {{ old('security_need_c') == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ old('security_need_c') == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ old('security_need_c') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ old('security_need_c') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ old('security_need_c') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ old('security_need_c') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
                            <td align="right">
                                <label for="security_need">{{ trans('global.integrity_short') }}</label>
                            </td>
                            <td width="120">
                                <select class="form-control select2 risk {{ $errors->has('security_need_i') ? 'is-invalid' : '' }}"
                                        name="security_need_i" id="security_need_i">
                                    <option value="-1" {{ old('security_need_i') == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ old('security_need_i') == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ old('security_need_i') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ old('security_need_i') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ old('security_need_i') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ old('security_need_i') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
                            <td align="right">
                                <label for="security_need">{{ trans('global.availability_short') }}</label>
                            </td>
                            <td width="120">
                                <select class="form-control select2 risk {{ $errors->has('security_need_a') ? 'is-invalid' : '' }}"
                                        name="security_need_a" id="security_need_a">
                                    <option value="-1" {{ old('security_need_a') == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ old('security_need_a') == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ old('security_need_a') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ old('security_need_a') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ old('security_need_a') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ old('security_need_a') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
                            <td align="right">
                                <label for="security_need">{{ trans('global.tracability_short') }}</label>
                            </td>
                            <td width="120">
                                <select class="form-control select2 risk {{ $errors->has('security_need_t') ? 'is-invalid' : '' }}"
                                        name="security_need_t" id="security_need_t">
                                    <option value="-1" {{ old('security_need_t') == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ old('security_need_t') == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ old('security_need_t') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ old('security_need_t') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ old('security_need_t') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ old('security_need_t') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
                            @if (config('mercator-config.parameters.security_need_auth'))
                                <td align="right">
                                    <label for="security_need">{{ trans('global.authenticity_short') }}</label>
                                </td>
                                <td width="120">
                                    <select class="form-control select2 risk {{ $errors->has('security_need_auth') ? 'is-invalid' : '' }}"
                                            name="security_need_auth" id="security_need_auth">
                                        <option value="-1" {{ old('security_need_auth') == -1 ? 'selected' : '' }}></option>
                                        <option value="0" {{ old('security_need_auth') == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ old('security_need_auth') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ old('security_need_auth') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ old('security_need_auth') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ old('security_need_auth') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                            @endif
                        </tr>
                    </table>
                    @if($errors->has('security_need'))
                        <div class="invalid-feedback">
                            {{ $errors->first('security_need') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.database.fields.security_need_helper') }}</span>
                </div>

            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.databases.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
