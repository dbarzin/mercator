@extends('layouts.admin')

@section('content')
    <form method="POST" action="{{ route('admin.applications.update', [$application->id]) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.application.title_singular') }}
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.application.fields.name') }}</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name', $application->name) }}" required maxlength="32" autofocus/>
                            <span class="help-block">{{ trans('cruds.application.fields.name_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label class="recommended"
                                   for="application_block_id">{{ trans('cruds.application.fields.application_block') }}</label>
                            <select class="form-control select2 {{ $errors->has('application_block') ? 'is-invalid' : '' }}"
                                    name="application_block_id" id="application_block_id">
                                <option value="">...</option>
                                @foreach($applicationBlocks as $id => $applicationBlock)
                                    <option value="{{ $id }}" {{ ($application->applicationBlock ? $application->applicationBlock->id : old('application_block_id')) == $id ? 'selected' : '' }}>{{ $applicationBlock }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('application_block'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('application_block') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.application_block_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="attributes">{{ trans('cruds.application.fields.attributes') }}</label>
                            <select class="form-control select2-free {{ $errors->has('patching_group') ? 'is-invalid' : '' }}"
                                    name="attributes[]" id="attributes" multiple>
                                @foreach($attributes_list as $a)
                                    <option {{ ( (old('attributes')!=null) && in_array($a,old('attributes'))) || in_array($a, explode(' ',$application->attributes)) ? 'selected' : '' }}>{{$a}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('attributes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('attributes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.attributes_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-9">
                        <div class="form-group">
                            <label class="recommended"
                                   for="description">{{ trans('cruds.application.fields.description') }}</label>
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    name="description"
                                    id="description">{!! old('description', $application->description) !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.description_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="iconSelect">{{ trans('global.icon_select') }}</label>
                            <select id="iconSelect"
                                    name="iconSelect"
                                    class="form-control js-icon-picker"
                                    data-icons='@json($icons)'
                                    data-selected="{{ $application->icon_id ?? '-1' }}"
                                    data-default-img="{{ asset('images/application.png') }}"
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
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.menu.ecosystem.title_short") }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="recommended"
                                   for="responsible">{{ trans('cruds.application.fields.responsible') }}</label>
                            <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}"
                                    name="responsibles[]" id="responsibles" multiple>
                                @foreach($responsible_list as $resp)
                                    <option {{ str_contains($application->responsible ,$resp) ? 'selected' : '' }}>{{$resp}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('responsible'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('responsible') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.responsible_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="recommended"
                                   for="entity_resp_id">{{ trans('cruds.application.fields.entity_resp') }}</label>
                            <select class="form-control select2 {{ $errors->has('entity_resp') ? 'is-invalid' : '' }}"
                                    name="entity_resp_id" id="entity_resp_id">
                                <option value="">...</option>
                                @foreach($entities as $id => $name)
                                    <option value="{{ $id }}" {{ ($application->entityResp ? $application->entityResp->id : old('entity_resp_id')) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('entity_resp'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entity_resp') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.entity_resp_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="recommended"
                                   for="entities">{{ trans('cruds.application.fields.entities') }}</label>
                            <select class="form-control select2 {{ $errors->has('entities') ? 'is-invalid' : '' }}"
                                    name="entities[]" id="entities" multiple>
                                @foreach($entities as $id => $entity)
                                    <option value="{{ $id }}" {{ (in_array($id, old('entities', [])) || $application->entities->contains($id)) ? 'selected' : '' }}>{{ $entity }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('entities'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entities') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.entities_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="referent">{{ trans('cruds.application.fields.functional_referent') }}</label>
                            <select class="form-control select2-free {{ $errors->has('functional_referent') ? 'is-invalid' : '' }}"
                                    name="functional_referent" id="referent">
                                @if (!$referent_list->contains(old('functional_referent')))
                                    <option> {{ old('functional_referent') }}</option>
                                @endif
                                @foreach($referent_list as $t)
                                    <option {{ (old('functional_referent') ? old('functional_referent') : $application->functional_referent) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('functional_referent'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('functional_referent') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.functional_referent_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="editor">{{ trans('cruds.application.fields.editor') }}</label>
                            <select class="form-control select2-free {{ $errors->has('editor') ? 'is-invalid' : '' }}"
                                    name="editor" id="editor">
                                @if (!$editor_list->contains(old('editor')))
                                    <option> {{ old('editor') }}</option>
                                @endif
                                @foreach($editor_list as $t)
                                    <option {{ (old('editor') ? old('editor') : $application->editor) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('editor'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('editor') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.editor_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label class="recommended" for="users">{{ trans('cruds.application.fields.users') }}</label>
                            <select class="form-control select2-free {{ $errors->has('users') ? 'is-invalid' : '' }}"
                                    name="users" id="users">
                                @if (!$type_list->contains(old('users')))
                                    <option> {{ old('users') }}</option>
                                @endif
                                @foreach($users_list as $t)
                                    <option {{ (old('users') ? old('users') : $application->users) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('users'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('users') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.users_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="cartographers">{{ trans('cruds.application.fields.cartographers') }}</label>
                            <select class="form-control select2-free {{ $errors->has('cartographers') ? 'is-invalid' : '' }}"
                                    name="cartographers[]" id="cartographers" multiple>
                                @foreach($cartographers_list as $id => $cartographer)
                                    @if(null !== old('cartographers'))
                                        <option value="{{ $id }}" {{ in_array($id, old('cartographers', [])) ? 'selected' : '' }}>{{ $cartographer }}</option>
                                    @else
                                        <option value="{{ $id }}" {{ $application->cartographers->contains($id) && !$errors->any() ? 'selected' : '' }}>{{ $cartographer }}</option>
                                    @endif
                                @endforeach
                            </select>
                            @if($errors->has('cartographers'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('cartographer') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.cartographers_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="logical_servers">{{ trans('cruds.application.fields.administrators') }}</label>
                            <select class="form-control select2 {{ $errors->has('administrators') ? 'is-invalid' : '' }}"
                                    name="administrators[]" id="administrators" multiple>
                                @foreach($users as $id => $name)
                                    <option value="{{ $id }}" {{ (in_array($id, old('administrators', [])) || $application->administrators->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('logical_servers'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('logical_servers') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.administrators_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans("cruds.application.title_singular") }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label class="recommended"
                                   for="technology">{{ trans('cruds.application.fields.technology') }}</label>
                            <select class="form-control select2-free {{ $errors->has('technology') ? 'is-invalid' : '' }}"
                                    name="technology" id="technology">
                                @if (!$technology_list->contains(old('technology')))
                                    <option> {{ old('technology') }}</option>
                                @endif
                                @foreach($technology_list as $t)
                                    <option {{ (old('technology') ? old('technology') : $application->technology) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('technology'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('technology') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.technology_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="recommended" for="type">{{ trans('cruds.application.fields.type') }}</label>
                            <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}"
                                    name="type" id="type">
                                @if (!$type_list->contains(old('type')))
                                    <option> {{ old('type') }}</option>
                                @endif
                                @foreach($type_list as $t)
                                    <option {{ (old('type') ? old('type') : $application->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('type') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.type_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label class="recommended"
                                   for="external">{{ trans('cruds.application.fields.external') }}</label>
                            <select class="form-control select2-free {{ $errors->has('external') ? 'is-invalid' : '' }}"
                                    name="external" id="external">
                                @if (!$type_list->contains(old('external')))
                                    <option> {{ old('external') }}</option>
                                @endif
                                @foreach($external_list as $t)
                                    <option {{ (old('external') ? old('external') : $application->external) == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('external'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('external') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.external_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="install_date">{{ trans('cruds.application.fields.install_date') }}</label>
                            <input class="form-control date" type="date" name="install_date" id="install_date"
                                   value="{{ old('install_date', $application->install_date) }}">
                            <span class="help-block">{{ trans('cruds.application.fields.install_date_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="update_date">{{ trans('cruds.application.fields.update_date') }}</label>
                            <input class="form-control date" type="date" id="update_date" name="update_date"
                                   value="{{ old('update_date', $application->update_date) }}">
                            <span class="help-block">{{ trans('cruds.application.fields.update_date_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="event_desc">{{ trans('cruds.application.fields.events') }}</label>
                            <div class="row">
                                <div class="col-12">
                                    <textarea id="eventMessage" class="textarea-custom-size" rows="2"
                                              placeholder="{{ trans('cruds.application.fields.events_placeholder') }}"
                                              class="form-control" type="text" id="event_desc" value=""></textarea>
                                    <a href="#" id="addEventBtn"
                                       class="btn btn-danger my-2">{{ trans('cruds.application.fields.events_add') }}</a>
                                    <button class="btn btn-info my-3 events_list_button">
                                        {{ trans('cruds.application.fields.events_list_button') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="recommended"
                                   for="documentation">{{ trans('cruds.application.fields.documentation') }}</label>
                            <input class="form-control {{ $errors->has('documentation') ? 'is-invalid' : '' }}"
                                   type="text" name="documentation" id="documentation"
                                   value="{{ old('documentation', $application->documentation) }}">
                            @if($errors->has('documentation'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('documentation') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.documentation_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="databases">{{ trans('cruds.application.fields.databases') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('databases') ? 'is-invalid' : '' }}"
                                    name="databases[]" id="databases" multiple>
                                @foreach($databases as $id => $databases)
                                    <option value="{{ $id }}" {{ (in_array($id, old('databases', [])) || $application->databases->contains($id)) ? 'selected' : '' }}>{{ $databases }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('databases'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('databases') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.databases_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label for="services">{{ trans('cruds.application.fields.services') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('services') ? 'is-invalid' : '' }}"
                                    name="services[]" id="services" multiple>
                                @foreach($services as $id => $services)
                                    <option value="{{ $id }}" {{ (in_array($id, old('services', [])) || $application->services->contains($id)) ? 'selected' : '' }}>{{ $services }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('services'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('services') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.services_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                {{ trans('cruds.application.title_security') }}
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-7">
                        <div class="form-group">
                            <label
                                    @if (auth()->user()->granularity>=2)
                                        class="recommended"
                                    @endif
                                    for="security_need">{{ trans('cruds.application.fields.security_need') }}</label>
                            <table cellspacing="5" cellpadding="5" border="0" width='100%'>
                                <tr>
                                    <td align="right" valign="bottom">
                                        <label for="security_need">{{ trans("global.confidentiality_short") }}</label>
                                    </td>
                                    <td>
                                        <span class="help-block">{{ trans("global.confidentiality") }}</span>
                                        <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}"
                                                name="security_need_c" id="security_need_c">
                                            <option value="-1" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == -1 ? 'selected' : '' }}></option>
                                            <option value="0" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                            <option value="1" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                            <option value="2" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                            <option value="3" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                            <option value="4" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                        </select>
                                    </td>
                                    <td align="right" valign="bottom">
                                        <label for="security_need">{{ trans("global.integrity_short") }}</label>
                                    </td>
                                    <td>
                                        <span class="help-block">{{ trans("global.integrity") }}</span>
                                        <select class="form-control select2 risk {{ $errors->has('security_need_i') ? 'is-invalid' : '' }}"
                                                name="security_need_i" id="security_need_i">
                                            <option value="-1" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == -1 ? 'selected' : '' }}></option>
                                            <option value="0" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                            <option value="1" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                            <option value="2" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                            <option value="3" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                            <option value="4" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                        </select>
                                    </td>
                                    <td align="right" valign="bottom">
                                        <label for="security_need">{{ trans("global.availability_short") }}</label>
                                    </td>
                                    <td>
                                        <span class="help-block">{{ trans("global.availability") }}</span>
                                        <select class="form-control select2 risk {{ $errors->has('security_need_a') ? 'is-invalid' : '' }}"
                                                name="security_need_a" id="security_need_a">
                                            <option value="-1" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == -1 ? 'selected' : '' }}></option>
                                            <option value="0" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                            <option value="1" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                            <option value="2" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                            <option value="3" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                            <option value="4" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                        </select>
                                    </td>
                                    <td align="right" valign="bottom">
                                        <label for="security_need">{{ trans("global.tracability_short") }}</label>
                                    </td>
                                    <td>
                                        <span class="help-block">{{ trans("global.tracability") }}</span>
                                        <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}"
                                                name="security_need_t" id="security_need_t">
                                            <option value="-1" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == -1 ? 'selected' : '' }}></option>
                                            <option value="0" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                            <option value="1" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                            <option value="2" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                            <option value="3" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                            <option value="4" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                        </select>
                                    </td>
                                    @if (config('mercator-config.parameters.security_need_auth'))
                                        <td align="right" valign="bottom">
                                            <label for="security_need">{{ trans("global.authenticity_short") }}</label>
                                        </td>
                                        <td>
                                            <span class="help-block">{{ trans("global.authenticity") }}</span>
                                            <select class="form-control select2 risk{{ $errors->has('security_need_auth') ? 'is-invalid' : '' }}"
                                                    name="security_need_auth" id="security_need_auth">
                                                <option value="-1"></option>
                                                <option value="0" {{ ($application->security_need_auth ? $application->security_need_auth : old('security_need_auth')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                                <option value="1" {{ ($application->security_need_auth ? $application->security_need_auth : old('security_need_auth')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                                <option value="2" {{ ($application->security_need_auth ? $application->security_need_auth : old('security_need_auth')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                                <option value="3" {{ ($application->security_need_auth ? $application->security_need_auth : old('security_need_auth')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                                <option value="4" {{ ($application->security_need_auth ? $application->security_need_auth : old('security_need_auth')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
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
                            <span class="help-block">{{ trans('cruds.application.fields.security_need_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label>{{ trans('cruds.application.fields.RTO') }}</label>
                            <table>
                                <tr>
                                    <td>
                                        <span class="help-block">{{ trans('global.days') }}</span>
                                        <input type="number" class="form-control" id="rto_days" name="rto_days" min="0"
                                               max="365" value="{{ old('rto_days', $application->rto_days) }}">
                                    </td>
                                    <td>
                                        <span class="help-block">{{ trans('global.hours') }}</span>
                                        <input type="number" class="form-control" id="rto_hours" name="rto_hours"
                                               min="0" max="24" value="{{ old('rto_hours', $application->rto_hours) }}">
                                    </td>
                                    <td>
                                        <span class="help-block">{{ trans('global.minutes') }}</span>
                                        <input type="number" class="form-control" id="rto_minutes" name="rto_minutes"
                                               min="0" max="60"
                                               value="{{ old('rto_minutes', $application->rto_minutes) }}">
                                    </td>
                                </tr>
                            </table>
                            <span class="help-block">{{ trans('cruds.application.fields.RTO_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm">
                        <div class="form-group">
                            <label>{{ trans('cruds.application.fields.RPO') }}</label>
                            <table>
                                <tr>
                                    <td>
                                        <span class="help-block">{{ trans('global.days') }}</span>
                                        <input type="number" class="form-control" id="rpo_days" name="rpo_days" min="0"
                                               max="365" value="{{ old('rpo_days', $application->rpo_days) }}">
                                    </td>
                                    <td>
                                        <span class="help-block">{{ trans('global.hours') }}</span>
                                        <input type="number" class="form-control" id="rpo_hours" name="rpo_hours"
                                               min="0"
                                               max="24" value="{{ old('rpo_hours', $application->rpo_hours) }}">
                                    </td>
                                    <td>
                                        <span class="help-block">{{ trans('global.minutes') }}</span>
                                        <input type="number" class="form-control" id="rpo_minutes" name="rpo_minutes"
                                               min="0"
                                               max="60" value="{{ old('rpo_minutes', $application->rpo_minutes) }}">
                                    </td>
                                </tr>
                            </table>
                            <span class="help-block">{{ trans('cruds.application.fields.RPO_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-header">
                Common Platform Enumeration (CPE)
            </div>
            <!------------------------------------------------------------------------------------------------------------->
            <div class="card-body">
                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.application.fields.vendor') }}</label>
                            <select id="vendor-selector" class="form-control vendor-selector" name="vendor">
                                <option>{{ old('vendor', $application->vendor) }}</option>
                            </select>
                            <span class="help-block">{{ trans('cruds.application.fields.vendor_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="name">{{ trans('cruds.application.fields.product') }}</label>
                            <select id="product-selector" class="form-control product-selector" name="product">
                                <option>{{ old('name', $application->product) }}</option>
                            </select>
                            @if($errors->has('product'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('product') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.product_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="form-group">
                            <label for="version">{{ trans('cruds.application.fields.version') }}</label>
                            <select id="version-selector" class="form-control version-selector" name="version">
                                <option>{{ old('version', $application->version) }}</option>
                            </select>
                            @if($errors->has('version'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('version') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.version_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-1">
                        <div class="form-group">
                            <br>
                            <button type="button" class="btn btn-info" id="guess"
                                    alt="Guess vendor and product base on application name">Guess
                            </button>
                            <span class="help-block"></span>
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
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="recommended"
                                   for="processes">{{ trans('cruds.application.fields.processes') }}</label>
                            <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}"
                                    name="processes[]" id="processes" multiple>
                                @foreach($processes as $id => $process)
                                    <option value="{{ $id }}" {{ (in_array($id, old('processes', [])) || $application->processes->contains($id)) ? 'selected' : '' }}>{{ $process }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('processes'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('processes') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.processes_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label class="recommended"
                                   for="activities">{{ trans('cruds.application.fields.activities') }}</label>
                            <select class="form-control select2 {{ $errors->has('activities') ? 'is-invalid' : '' }}"
                                    name="activities[]" id="activities" multiple>
                                @foreach($activities as $id => $activity)
                                    <option value="{{ $id }}" {{ (in_array($id, old('activities', [])) || $application->activities->contains($id)) ? 'selected' : '' }}>{{ $activity }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('activities'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('activities') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.activities_helper') }}</span>
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
                            <label for="logical_servers">{{ trans('cruds.application.fields.logical_servers') }}</label>
                            <select class="form-control select2 {{ $errors->has('logical_servers') ? 'is-invalid' : '' }}"
                                    name="logical_servers[]" id="logical_servers" multiple>
                                @foreach($logical_servers as $id => $name)
                                    <option value="{{ $id }}" {{ (in_array($id, old('logical_servers', [])) || $application->logicalServers->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('logical_servers'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('logical_servers') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.logical_servers_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="logical_servers">{{ trans('cruds.application.fields.security_devices') }}</label>
                            <select class="form-control select2 {{ $errors->has('security_devices') ? 'is-invalid' : '' }}"
                                    name="security_devices[]" id="security_devices" multiple>
                                @foreach($security_devices as $id => $name)
                                    <option value="{{ $id }}" {{ (in_array($id, old('security_devices', [])) || $application->securityDevices->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('security_devices'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('security_devices') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.application.fields.security_devices_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.applications.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection

@section('scripts')
    @vite(['resources/js/cpe.js'])
    <script>
        document.addEventListener("DOMContentLoaded", function () {

            /*****************************************/
            /* CPE Search
            */
            window.cpePart = 'a';

            /**
             * Contruction de la liste des évènements
             * @returns {string}
             */
            async function fetchAndRenderEvents(applicationId) {
                try {
                    const response = await fetch(`/admin/application-events?id=${applicationId}`);
                    if (!response.ok) throw new Error('Erreur API');
                    const events = await response.json();

                    let ret = '<ul>';
                    events.forEach(function (event) {
                        ret += `
                        <li data-id="${event.id}" style="text-align: left; margin-bottom: 20px; position: relative">
                            <a class="delete_event" style="cursor: pointer; position: absolute; right: 0; top: 5px;" href="#">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                            ${event.message}<br>
                            <span style="font-size: 12px;">Date : ${moment(event.created_at).format('DD-MM-YYYY')} | Utilisateur : ${event.user.name}</span>
                        </li>
                    `;
                    });
                    ret += '</ul>';
                    return ret;

                } catch (error) {
                    console.error('Erreur lors de la récupération des événements :', error);
                    return '<p>Erreur lors du chargement des événements.</p>';
                }
            }

            $('.events_list_button').click(async function (e) {
                e.preventDefault();

                const htmlContent = await fetchAndRenderEvents({{ $application->id }});

                Swal.fire({
                    title: 'Évènements',
                    html: htmlContent,
                    didOpen: () => {
                        document.querySelectorAll('.delete_event').forEach(link => {
                            link.addEventListener('click', function (ev) {
                                ev.preventDefault();

                                const li = this.closest('li');
                                const eventId = li.getAttribute('data-id');

                                if (eventId) {
                                    fetch(`/admin/application-events/${eventId}`, {
                                        method: 'DELETE',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                        },
                                        body: JSON.stringify({m_application_id: {{ $application->id }}})
                                    }).then(resp => {
                                        if (!resp.ok) throw new Error();
                                        return resp.json();
                                    }).then(data => {
                                        li.remove();
                                        Swal.fire('Évènement supprimé !', '', 'success');
                                    }).catch(error => {
                                        console.error('Erreur suppression événement :', error);
                                        Swal.fire('Une erreur est survenue', error.message, 'error');
                                    });
                                }
                            });
                        });
                    }
                });
            });

            // Send AJAX for adding an event
            $('#addEventBtn').on('click', function (e) {
                e.preventDefault();
                let app_id = {{ $application->id }};
                let user_id = {{ auth()->id() }};
                let message = $('#eventMessage').val();
                if (message !== '' && user_id && app_id) {
                    $.post("{{ route("admin.application-events.store") }}", {
                        m_application_id: app_id,
                        user_id: user_id,
                        message: message,
                        _token: "{{ csrf_token() }}"
                    }, "json")
                        .done((data) => {
                            // Mise à jour des évènements pour la popup
                            swalHtml = data.events;
                            Swal.fire('Evènement ajouté !', '', 'success');
                            $('#eventMessage').val('');
                        })
                        .fail(() => {
                            Swal.fire('Une erreur est survenue', '', 'error');
                        })
                }
            });


            // submit the correct button when "enter" key pressed
            $("form input").keypress(function (e) {
                if ((e.which && e.which === 13) || (e.keyCode && e.keyCode === 13)) {
                    $('input[type=submit].default').click();
                    return false;
                } else {
                    return true;
                }
            });


        });
    </script>
@endsection
