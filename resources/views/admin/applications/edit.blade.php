@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.application.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.applications.update", [$application->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.application.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $application->name) }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.name_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="version">{{ trans('cruds.application.fields.version') }}</label>
                        <input class="form-control {{ $errors->has('version') ? 'is-invalid' : '' }}" type="text" name="version" id="version" value="{{ old('version', $application->version) }}">
                        @if($errors->has('version'))
                            <div class="invalid-feedback">
                                {{ $errors->first('version') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.version_helper') }}</span>
                    </div>
                </div>
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


          <div class="row">
            <div class="col-sm">
                @if (auth()->user()->granularity>=2)
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
                    @endif
                    <span class="help-block">{{ trans('cruds.application.fields.entities_helper') }}</span>
                </div>

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

                <div class="form-group">
                    <label class="recommended" for="responsible">{{ trans('cruds.application.fields.responsible') }}</label>
                    <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsible" id="responsible">
                        @if (!$responsible_list->contains(old('responsible')))
                            <option> {{ old('responsible') }}</option>'
                        @endif
                        @foreach($responsible_list as $t)
                            <option {{ (old('responsible') ? old('responsible') : $application->responsible) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.application.fields.responsible_helper') }}</span>
                </div>
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
            <div class="col-sm">
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

                @if (auth()->user()->granularity>=2)
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
                @endif

                <div class="form-group">
                    <label for="editor">{{ trans('cruds.application.fields.editor') }}</label>
                    <select class="form-control select2-free {{ $errors->has('editor') ? 'is-invalid' : '' }}" name="editor" id="referent">
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
            <div class="col-sm">

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
                <div class="form-group">
                     @php
                         $currentCartographers = array();
                         $cartographers = array();
                         foreach ($application->cartographers as $cartographer) {
                             $currentCartographers[] = $cartographer->id;
                         }

                         foreach ($cartographers_list as $key => $user) {
                             // S'il se trouve dans old ou qu'il se trouve dans les cartographers de base (sans erreurs de formulaire)
                             if((old('cartographers') !== null && in_array($key, old('cartographers', []))) || (in_array($key, $currentCartographers) && !$errors->any())) {
                                 $cartographers[] = [$key, $user, true]; // true = selected
                             } else {
                                 $cartographers[] = [$key, $user, false];
                             }
                         }
                    @endphp
                    <label class="recommended" for="cartographers">{{ trans('cruds.application.fields.cartographers') }}</label>
                    <select class="form-control select2-free {{ $errors->has('cartographers') ? 'is-invalid' : '' }}" name="cartographers[]" id="cartographers" multiple="multiple">
                        @foreach($cartographers as $cartographer)
                            <option value="{{ $cartographer[0] }}" {{ $cartographer[2] ? 'selected' : '' }}>{{ $cartographer[1] }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('cartographers'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cartographer') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.application.fields.cartographers_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="recommended" for="major_event">{{ trans('cruds.application.fields.major_events') }}</label>

                    <div class="row">
                        <div class="col-md-12"><strong>{{ trans('cruds.application.fields.major_events_last') }}</strong> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Adipisci aliquid autem beatae doloribus et iure officia quas recusandae. Commodi culpa debitis id inventore natus nesciunt nostrum numquam quaerat rerum unde?</div>
                    </div>
                    <button class="btn btn-info my-3 major_events_button">
                        {{ trans('cruds.application.fields.major_events_button') }}
                    </button>
                    <div class="row">
                        <div class="col-md-12">
                            <textarea placeholder="{{ trans('cruds.application.fields.major_events_placeholder') }}" class="form-control {{ $errors->has('major_event') ? 'is-invalid' : '' }}" type="text" name="major_event" id="major_event" value="{{ old('major_event', $application->major_event) }}"></textarea>
                            <button class="btn btn-danger my-2">{{ trans('cruds.application.fields.major_events_add') }}</button>
                        </div>

                    </div>
                    @if($errors->has('major_events'))
                        <div class="invalid-feedback">
                            {{ $errors->first('major_event') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.application.fields.major_events_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="install_date">{{ trans('cruds.application.fields.install_date') }}</label>
                    <input class="form-control datetime" type="text" name="install_date" id="install_date" value="{{ old('install_date', $application->install_date) }}">
                    <span class="help-block">{{ trans('cruds.application.fields.install_date_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="update_date">{{ trans('cruds.application.fields.update_date') }}</label>
                    <input class="datetime form-control" type="text" id="update_date" name="update_date" value="{{ old('update_date', $application->update_date) }}">
                    <span class="help-block">{{ trans('cruds.application.fields.update_date_helper') }}</span>
                </div>
            </div>
        </div>

          <div class="row">
            <div class="col-sm">
                    <div class="form-group">
                        <table cellspacing="5" cellpadding="5" border="0" width='100%'>
                            <tr>
                                <td width='20%'>
                                    <label
                                        @if (auth()->user()->granularity>=2)
                                        class="recommended"
                                        @endif
                                        for="security_need">{{ trans('cruds.application.fields.security_need') }}</label>
                                </td>
                                <td align="right" width="10">
                                    <label for="security_need">C</label>
                                </td>
                                <td  width="120">
                                    <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}" name="security_need_c" id="security_need_c">
                                        <option value="-1" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == -1 ? 'selected' : '' }}></option>
                                        <option value="0" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ ($application->security_need_c ? $application->security_need_c : old('security_need_c')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">I</label>
                                </td>
                                <td  width="120">
                                    <select class="form-control select2 risk {{ $errors->has('security_need_i') ? 'is-invalid' : '' }}" name="security_need_i" id="security_need_i">
                                        <option value="-1" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == -1 ? 'selected' : '' }}></option>
                                        <option value="0" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ ($application->security_need_i ? $application->security_need_i : old('security_need_i')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">D</label>
                                </td>
                                <td  width="120">
                                    <select class="form-control select2 risk {{ $errors->has('security_need_a') ? 'is-invalid' : '' }}" name="security_need_a" id="security_need_a">
                                        <option value="-1" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == -1 ? 'selected' : '' }}></option>
                                        <option value="0" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ ($application->security_need_a ? $application->security_need_a : old('security_need_a')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">T</label>
                                </td>
                                <td  width="120">
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
                        <span class="help-block">{{ trans('cruds.application.fields.security_need_helper') }}</span>                    </div>

                </div>
                <div class="col-sm">
                </div>
        </div>


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
        <div class="form-group">
            @if (auth()->user()->granularity>=2)
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
            @endif
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function () {
    $('.major_events_button').click(function(e) {
        e.preventDefault()
        Swal.fire({
            title: 'Évènements',
            icon: 'info',
            html:
                '<ul>' +
                    '<li style="text-align: left; margin-bottom: 20px;">Evenement 1<br>' +
                    '<span style="font-size: 12px;">Date : 19/10/22 | Utilisateur : User 1</span>' +
                    '<li style="text-align: left; margin-bottom: 20px;">Evenement 1<br>' +
                    '<span style="font-size: 12px;">Date : 19/10/22 | Utilisateur : User 1</span>' +
                    '<li style="text-align: left; margin-bottom: 20px;">Evenement 1<br>' +
                    '<span style="font-size: 12px;">Date : 19/10/22 | Utilisateur : User 1</span>' +
                    '<li style="text-align: left; margin-bottom: 20px;">Evenement 1<br>' +
                    '<span style="font-size: 12px;">Date : 19/10/22 | Utilisateur : User 1</span>' +
                    '<li style="text-align: left; margin-bottom: 20px;">Evenement 1<br>' +
                    '<span style="font-size: 12px;">Date : 19/10/22 | Utilisateur : User 1</span>' +
                '</ul>',
            showCloseButton: true,
        })
    })
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
    })

function template(data, container) {
      if (data.id==4) {
         return '\<span class="highRisk"\>'+data.text+'</span>';
      } else if (data.id==3) {
         return '\<span class="mediumRisk"\>'+data.text+'</span>';
      } else if (data.id==2) {
         return '\<span class="lowRisk"\>'+data.text+'</span>';
      } else if (data.id==1) {
         return '\<span class="veryLowRisk"\>'+data.text+'</span>';
      } else {
         return data.text;
      }
    }

    $('.risk').select2({
      templateSelection: template,
      escapeMarkup: function(m) {
          return m;
      }
    });

  });
</script>
@endsection
