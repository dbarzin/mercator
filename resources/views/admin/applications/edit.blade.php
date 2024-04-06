@extends('layouts.admin')

@section('content')
<form method="POST" action="{{ route("admin.applications.update", [$application->id]) }}" enctype="multipart/form-data">
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
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.application.fields.name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" min="0" value="{{ old('name', $application->name) }}">
                        <span class="help-block">{{ trans('cruds.application.fields.name_helper') }}</span>
                    </div>
                </div>
                @if (auth()->user()->granularity>=2)
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="application_block_id">{{ trans('cruds.application.fields.application_block') }}</label>
                        <select class="form-control select2 {{ $errors->has('application_block') ? 'is-invalid' : '' }}" name="application_block_id" id="application_block_id">
                            @foreach($application_blocks as $id => $application_block)
                            <option value="{{ $id }}" {{ ($application->application_block ? $application->application_block->id : old('application_block_id')) == $id ? 'selected' : '' }}>{{ $application_block }}</option>
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
                @endif
            </div>
            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.application.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $application->description) !!}</textarea>
                @if($errors->has('description'))
                <div class="invalid-feedback">
                    {{ $errors->first('description') }}
                </div>
                @endif
                <span class="help-block">{{ trans('cruds.application.fields.description_helper') }}</span>
            </div>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans("cruds.menu.ecosystem.title_short") }}
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="responsible">{{ trans('cruds.application.fields.responsible') }}</label>
                        <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsibles[]" id="responsibles" multiple>
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
                @if (auth()->user()->granularity>=2)
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="entity_resp_id">{{ trans('cruds.application.fields.entity_resp') }}</label>
                        <select class="form-control select2 {{ $errors->has('entity_resp') ? 'is-invalid' : '' }}" name="entity_resp_id" id="entity_resp_id">
                            @foreach($entity_resps as $id => $entity_resp)
                                <option value="{{ $id }}" {{ ($application->entity_resp ? $application->entity_resp->id : old('entity_resp_id')) == $id ? 'selected' : '' }}>{{ $entity_resp }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('entity_resp'))
                        <div class="invalid-feedback">
                            {{ $errors->first('entity_resp') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.entity_resp_helper') }}</span>
                    </div>
                    @endif
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="entities">{{ trans('cruds.application.fields.entities') }}</label>
                        <select class="form-control select2 {{ $errors->has('entities') ? 'is-invalid' : '' }}" name="entities[]" id="entities" multiple>
                            @foreach($entities as $id => $entity)
                                <option value="{{ $id }}" {{ (in_array($id, old('entities', [])) || $application->entities->contains($id)) ? 'selected' : '' }}>{{ $entity }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('entities'))
                        <div class="invalid-feedback">
                            {{ $errors->first('entities') }}
                        </div>
                        <span class="help-block">{{ trans('cruds.application.fields.entities_helper') }}</span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="referent">{{ trans('cruds.application.fields.functional_referent') }}</label>
                    <select class="form-control select2-free {{ $errors->has('functional_referent') ? 'is-invalid' : '' }}" name="functional_referent" id="referent">
                        @if (!$referent_list->contains(old('functional_referent')))
                            <option> {{ old('functional_referent') }}</option>'
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
            <div class="col-md-4">
                <div class="form-group">
                    <label for="editor">{{ trans('cruds.application.fields.editor') }}</label>
                    <select class="form-control select2-free {{ $errors->has('editor') ? 'is-invalid' : '' }}" name="editor" id="editor">
                        @if (!$editor_list->contains(old('editor')))
                            <option> {{ old('editor') }}</option>'
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

            @if (auth()->user()->granularity>=2)
            <div class="col-md-4">
                <div class="form-group">
                    <label class="recommended" for="users">{{ trans('cruds.application.fields.users') }}</label>
                    <select class="form-control select2-free {{ $errors->has('users') ? 'is-invalid' : '' }}" name="users" id="users">
                        @if (!$type_list->contains(old('users')))
                            <option> {{ old('users') }}</option>'
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
            @endif

            <div class="col-md-4">
                <div class="form-group">
                 <label for="cartographers">{{ trans('cruds.application.fields.cartographers') }}</label>
                 <select class="form-control select2-free {{ $errors->has('cartographers') ? 'is-invalid' : '' }}" name="cartographers[]" id="cartographers" multiple>
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
        </div>
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        Technique
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">

            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="technology">{{ trans('cruds.application.fields.technology') }}</label>
                        <select class="form-control select2-free {{ $errors->has('technology') ? 'is-invalid' : '' }}" name="technology" id="technology">
                            @if (!$technology_list->contains(old('technology')))
                            <option> {{ old('technology') }}</option>'
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="type">{{ trans('cruds.application.fields.type') }}</label>
                        <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                            @if (!$type_list->contains(old('type')))
                            <option> {{ old('type') }}</option>'
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="external">{{ trans('cruds.application.fields.external') }}</label>
                        <select class="form-control select2-free {{ $errors->has('external') ? 'is-invalid' : '' }}" name="external" id="external">
                            @if (!$type_list->contains(old('external')))
                            <option> {{ old('external') }}</option>'
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
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="install_date">{{ trans('cruds.application.fields.install_date') }}</label>
                        <input class="form-control datetime" type="text" name="install_date" id="install_date" value="{{ old('install_date', $application->install_date) }}">
                        <span class="help-block">{{ trans('cruds.application.fields.install_date_helper') }}</span>
                    </div>

                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="update_date">{{ trans('cruds.application.fields.update_date') }}</label>
                        <input class="datetime form-control" type="text" id="update_date" name="update_date" value="{{ old('update_date', $application->update_date) }}">
                        <span class="help-block">{{ trans('cruds.application.fields.update_date_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="event_desc">{{ trans('cruds.application.fields.events') }}</label>
                        <div class="row">
                            <div class="col-md-12">
                                <textarea id="eventMessage" class="textarea-custom-size" rows="2" placeholder="{{ trans('cruds.application.fields.events_placeholder') }}" class="form-control" type="text" id="event_desc" value=""></textarea>
                                <a href="#" id="addEventBtn" class="btn btn-danger my-2">{{ trans('cruds.application.fields.events_add') }}</a>
                                <button class="btn btn-info my-3 events_list_button">
                                    {{ trans('cruds.application.fields.events_list_button') }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    @if (auth()->user()->granularity>=2)
                    <div class="form-group">
                        <label class="recommended" for="documentation">{{ trans('cruds.application.fields.documentation') }}</label>
                        <input class="form-control {{ $errors->has('documentation') ? 'is-invalid' : '' }}" type="text" name="documentation" id="documentation" value="{{ old('documentation', $application->documentation) }}">
                        @if($errors->has('documentation'))
                        <div class="invalid-feedback">
                            {{ $errors->first('documentation') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.documentation_helper') }}</span>
                    </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="databases">{{ trans('cruds.application.fields.databases') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('databases') ? 'is-invalid' : '' }}" name="databases[]" id="databases" multiple>
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
                    @if (auth()->user()->granularity>=2)
                    <div class="form-group">
                        <label for="services">{{ trans('cruds.application.fields.services') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('services') ? 'is-invalid' : '' }}" name="services[]" id="services" multiple>
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
                    @endif
                </div>
            </div>
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-header">
            Sécurité
        </div>
        <!------------------------------------------------------------------------------------------------------------->
        <div class="card-body">

          <div class="row">
            <div class="col-md-6">
                <label
                @if (auth()->user()->granularity>=2)
                class="recommended"
                @endif
                for="security_need">{{ trans('cruds.application.fields.security_need') }}</label>
                <div class="form-group">
                    <table cellspacing="5" cellpadding="5" border="0" width='100%'>
                        <tr>
                            <td align="right" valign="bottom">
                                <label for="security_need">{{ trans("global.confidentiality_short") }}</label>
                            </td>
                            <td>
                                <span class="help-block">{{ trans("global.confidentiality") }}</span>
                                <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}" name="security_need_c" id="security_need_c">
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
                                <select class="form-control select2 risk {{ $errors->has('security_need_i') ? 'is-invalid' : '' }}" name="security_need_i" id="security_need_i">
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
                                <select class="form-control select2 risk {{ $errors->has('security_need_a') ? 'is-invalid' : '' }}" name="security_need_a" id="security_need_a">
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
                                <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}" name="security_need_t" id="security_need_t">
                                    <option value="-1" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ ($application->security_need_t ? $application->security_need_t : old('security_need_t')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
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
                                <input type="number" class="form-control" id="rto_days" name="rto_days" min="0" value="{{ old('rto_days', $application->rto_days) }}">
                            </td>
                            <td>
                                <span class="help-block">{{ trans('global.hours') }}</span>
                                <input type="number" class="form-control" id="rto_hours" name="rto_hours" min="0" max="24" value="{{ old('rto_hours', $application->rto_hours) }}">
                            </td>
                            <td>
                                <span class="help-block">{{ trans('global.minutes') }}</span>
                                <input type="number" class="form-control" id="rto_minutes" name="rto_minutes" min="0" max="60" value="{{ old('rto_minutes', $application->rto_minutes) }}">
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
                                <input type="number" class="form-control" id="rpo" name="rpo_days" min="0" value="{{ old('rpo_days', $application->rpo_days) }}">
                            </td>
                            <td>
                                <span class="help-block">{{ trans('global.hours') }}</span>
                                <input type="number" class="form-control" id="rpo" name="rpo_hours" min="0" max="24" value="{{ old('rpo_hours', $application->rpo_hours) }}">
                            </td>
                            <td>
                                <span class="help-block">{{ trans('global.minutes') }}</span>
                                <input type="number" class="form-control" id="rpo" name="rpo_minutes" min="0" max="60" value="{{ old('rpo_minutes', $application->rpo_minutes) }}">
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
        Common Plateforme Enumeration (CPE)
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="name">{{ trans('cruds.application.fields.vendor') }}</label>
                    <div class="form-group">
                        <select id="vendor-selector" class="form-control select2-free" name="vendor">
                            <option>{{ old('vendor', $application->vendor) }}</option>
                        </select>
                        <span class="help-block">{{ trans('cruds.application.fields.vendor_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="name">{{ trans('cruds.application.fields.product') }}</label>
                    <select id="product-selector" class="form-control select2-free" name="product">
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
            <div class="col-md-3">
                <div class="form-group">
                    <label for="version">{{ trans('cruds.application.fields.version') }}</label>
                    <select id="version-selector" class="form-control select2-free" name="version">
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
            <div class="col-md-1">
                <div class="form-group">
                    <br>
                    <button type="button" class="btn btn-info" id="guess" alt="Guess vendor and product base on application name">Guess</button>
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
        <div class="col-sm">
            <div class="form-group">
                <label class="recommended" for="processes">{{ trans('cruds.application.fields.processes') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
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
      </div>
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.logical_infrastructure.title_short") }}
    </div>
    <!------------------------------------------------------------------------------------------------------------->
    <div class="card-body">
      <div class="row">
        <div class="col-sm">
            <div class="form-group">
                <label for="logical_servers">{{ trans('cruds.application.fields.logical_servers') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('logical_servers') ? 'is-invalid' : '' }}" name="logical_servers[]" id="logical_servers" multiple>
                    @foreach($logical_servers as $id => $logical_servers)
                    <option value="{{ $id }}" {{ (in_array($id, old('logical_servers', [])) || $application->logical_servers->contains($id)) ? 'selected' : '' }}>{{ $logical_servers }}</option>
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
    </div>
    </div>
    </div>
    <div class="form-group">
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>

@endsection

@section('scripts')
    <script>
        $(document).ready(function () {

        /**
         * Contruction de la liste des évènements
         * @returns {string}
         */
        function generateEventsList() {
            let ret = '<ul>';
            @json($application->events).forEach (function(event) {
                ret += '<li data-id="'+event.id+'" style="text-align: left; margin-bottom: 20px; position: relative">';
                ret += '<a class="delete_event" style="cursor: pointer; position: absolute;right: 0;top: 5px;" href="#">';
                ret += '<i data-toggle="wy-nav-top" class="fa fa-times"></i></a>'+event.message+'</br>';
                ret += '<span style="font-size: 12px;">Date : '+ moment(event.created_at).format('DD-MM-YYYY')
                ret += ' | Utilisateur : '+event.user.name+'</span>';
            });
            ret += '</ul>';
            return ret;
        }

        /**
         * Fire the popup
         */
            $('.events_list_button').click(function(e) {
                e.preventDefault()
                Swal.fire({
                    title: 'Évènements',
                    // icon: 'info',
                    html: generateEventsList(),
                    didOpen(popup) {
                        $('.delete_event').on('click', function(e) {
                            e.preventDefault();
                            let event_id = $(this).parent().data('id');
                            var that = $(this);
                            if (event_id) {
                                $.ajax({
                                    url: '/admin/application-events/' + event_id,
                                    type: "DELETE",
                                    data: {
                                        m_application_id: {{ $application->id }},
                                        _token: "{{ csrf_token() }}"
                                    },
                                    success: (data) => {
                                        that.parent().remove();
                                        // Mise à jour des évènements pour la popup
                                        swalHtml = data.events;
                                        Swal.fire('Evènement supprimé !', '', 'success');
                                    },
                                    error: () => {
                                        Swal.fire('Une erreur est survenue', '', 'error');
                                    }
                                })
                            }
                        });
                    }
                })
            })

        /**
         * Send AJAX for adding an event
         */
            $('#addEventBtn').on('click', function(e) {
                e.preventDefault();
                let app_id = {{ $application->id }};
                let user_id = {{ auth()->id() }};
                let message = $('#eventMessage').val();
                if(message !== '' && user_id && app_id) {
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

            var allEditors = document.querySelectorAll('.ckeditor');
            for (var i = 0; i < allEditors.length; ++i) {
                ClassicEditor.create(
                  allEditors[i], {
                    extraPlugins: []
                }
                );
            }

            $(".select2-free").select2({
                placeholder: "{{ trans('global.pleaseSelect') }}",
                allowClear: true,
                tags: true
            });

            $('.risk').select2({
                templateSelection: function(data, container) {
                    if (data.id==4)
                         return '\<span class="highRisk"\>'+data.text+'</span>';
                    else if (data.id==3)
                         return '\<span class="mediumRisk"\>'+data.text+'</span>';
                    else if (data.id==2)
                         return '\<span class="lowRisk"\>'+data.text+'</span>';
                    else if (data.id==1)
                         return '\<span class="veryLowRisk"\>'+data.text+'</span>';
                    else
                         return data.text;
                    },
                escapeMarkup: function(m) {
                  return m;
                }
            });

        // ------------------------------------------------
         $('#vendor-selector').select2({
          placeholder: 'Start typing to search',
          tags: true,
          ajax: {
            url: '/admin/cpe/search/vendors',
            data: function(params) {
              var query = {
                part: "a",
                search: params.term,
            };
            return query;
        },
        processResults: function(data) {
          var results = [];
          if (data.length) {
            $.each(data, function(id, vendor) {
              results.push({
                id: vendor.name,
                text: vendor.name
                });
            });
          }
        return {
            results: results
            };
            }
            }
        });

        // ------------------------------------------------
         $('#product-selector').select2({
          placeholder: 'Start typing to search',
          tags: true,
          ajax: {
            url: '/admin/cpe/search/products',
            data: function(params) {
              var query = {
                part: "a",
                vendor: $("#vendor-selector").val(),
                search: params.term,
            };
            return query;
            },
        processResults: function(data) {
          var results = [];
          if (data.length) {
            $.each(data, function(id, product) {
              results.push({
                id: product.name,
                text: product.name
            });
          });
        }
        return {
            results: results
            };
        }
        }
    });

        // ------------------------------------------------
         $('#version-selector').select2({
          placeholder: 'Start typing to search',
          tags: true,
          ajax: {
            url: '/admin/cpe/search/versions',
            data: function(params) {
              var query = {
                part: "a",
                vendor: $("#vendor-selector").val(),
                product: $("#product-selector").val(),
                search: params.term,
            };
            return query;
        },
        processResults: function(data) {
          var results = [];
          if (data.length) {
            $.each(data, function(id, version) {
              results.push({
                id: version.name,
                text: version.name
            });
          });
        }
        return {
            results: results
            };
        }
    }

    });

    // CPE Guesser
    // ===========
    function generateCPEList(data) {
        let ret = '<div style="max-height: 300px; overflow-y: scroll;">';
        ret += '<table class="table compact">'
        ret += '<thead><tr><th>Vendor</th><th>Product</th><th></th></tr></thead>';
        data.forEach (function(element) {
            ret += '<tr>';
            ret += '<td>' + element.vendor_name + '</td>';
            ret += '<td>' + element.product_name +'</td>';
            ret += '<td>' + '<a class="select_cpe" data-vendor="'+element.vendor_name+'" data-product="'+element.product_name+'" href="#"> <i class="fa fa-check" style="color:green"></i></a>'
            ret += '</td>';
            ret += '</tr>';
        });
        ret += '</table></div>';
        return ret;
    }

    // CPE Guesser window
    $('#guess').click(function (event) {
        let name = $("#name").val();
        $.get("/admin/cpe/search/guess?search="+encodeURIComponent(name))
        .then((result)=>
            Swal.fire({
                title: "Matching",
                html: generateCPEList(result),
                didOpen(popup) {
                    $('.select_cpe').on('click', function(e) {
                        e.preventDefault();
                        let vendor = $(this).data('vendor');
                        $("#vendor-selector").append('<option>'+vendor+'</option>');
                        $("#vendor-selector").val(vendor);
                        let product = $(this).data('product');
                        $("#product-selector").append('<option>'+product+'</option>');
                        $("#product-selector").val(product);
                        $("#version-selector").append('<option></option>');
                        $("#version-selector").val(null);
                        swal.close();
                    })
                },
                showConfirmButton: false,
                showCancelButton: true,
                customClass: {
                    container:   {
                        'max-height': "6em",
                        'overflow-y': 'scroll',
                        'width': '100%',
                    }
                }
            }));
        });

    // submit the correct button when "enter" key pressed
        $("form input").keypress(function (e) {
        if ((e.which && e.which == 13) || (e.keyCode && e.keyCode == 13)) {
            $('input[type=submit].default').click();
            return false;
        }
        else {
            return true;
        }
    });
});
</script>
@endsection
