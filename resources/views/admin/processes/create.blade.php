@extends('layouts.admin')
@section('content')

    <form method="POST" action="{{ route("admin.processes.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.process.title_singular') }}
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.process.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="name" id="name" value="{{ old('name', '') }}" maxlength=64 required autofocus/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.process.fields.name_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="form-group">
                            <label class="recommended2"
                                   for="macroprocessus_id">{{ trans('cruds.process.fields.macroprocessus') }}</label>
                            <select class="form-control select2 {{ $errors->has('macroprocessus') ? 'is-invalid' : '' }}"
                                    name="macroprocess_id" id="macroprocess_id">
                                <option></option>
                                @foreach($macroProcessuses as $id => $macroprocessus)
                                    <option value="{{ $id }}" {{ old('macroprocessus_id') == $id ? 'selected' : '' }}>{{ $macroprocessus }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('macroprocessus'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('macroprocessus') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.process.fields.macroprocessus_helper') }}</span>
                        </div>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-9">
                        <div class="form-group">
                            <label class="recommended1"
                                   for="description">{{ trans('cruds.process.fields.description') }}</label>
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    name="description" id="description">{!! old('description') !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.process.fields.description_helper') }}</span>
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
                                    data-default-img="{{ asset('images/process.png') }}"
                                    data-url-template="{{ route('admin.documents.show', ':id') }}"
                                    data-upload="#iconFile">
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="file" id="iconFile" name="iconFile" accept="image/png"/>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="recommended1" for="in_out">{{ trans('cruds.process.fields.in_out') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('in_out') ? 'is-invalid' : '' }}"
                              name="in_out" id="in_out">{!! old('in_out') !!}</textarea>
                    @if($errors->has('in_out'))
                        <div class="invalid-feedback">
                            {{ $errors->first('in_out') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.process.fields.in_out_helper') }}</span>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <table cellspacing="5" cellpadding="5" border="0" width='100%'>
                                <tr>
                                    <td width='20%' nowrap>
                                        <label class="recommended2"
                                               for="security_need">{{ trans('cruds.process.fields.security_need') }}</label>
                                    </td>
                                    <td align="right">
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
                                                <option value="-1"></option>
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
                            <span class="help-block">{{ trans('cruds.process.fields.security_need_helper') }}</span>
                        </div>

                    </div>


                    <div class="col-6">
                        <div class="form-group">
                            <label class="recommended1" for="owner">{{ trans('cruds.process.fields.owner') }}</label>
                            <select class="form-control select2-free {{ $errors->has('owner') ? 'is-invalid' : '' }}"
                                    name="owner" id="owner">
                                @if (!$owner_list->contains(old('owner')))
                                    <option> {{ old('owner') }}</option>
                                @endif
                                @foreach($owner_list as $t)
                                    <option {{ old('owner') == $t ? 'selected' : '' }}>{{$t}}</option>
                                @endforeach
                            </select>
                            @if($errors->has('owner'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('owner') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.process.fields.owner_helper') }}</span>
                        </div>
                    </div>
                </div>


                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="activities">{{ trans('cruds.process.fields.activities') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('activities') ? 'is-invalid' : '' }}"
                                    name="activities[]" id="activities" multiple>
                                @foreach($activities as $id => $activities)
                                    <option value="{{ $id }}" {{ in_array($id, old('activities', [])) ? 'selected' : '' }}>{{ $activities }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('activities'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('activities') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.process.fields.activities_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="entities">{{ trans('cruds.process.fields.entities') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('entities') ? 'is-invalid' : '' }}"
                                    name="entities[]" id="entities" multiple>
                                @foreach($entities as $id => $entities)
                                    <option value="{{ $id }}" {{ in_array($id, old('entities', [])) ? 'selected' : '' }}>{{ $entities }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('entities'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('entities') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.process.fields.entities_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="entities">{{ trans('cruds.process.fields.informations') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('informations') ? 'is-invalid' : '' }}"
                                    name="informations[]" id="informations" multiple>
                                @foreach($informations as $id => $informations)
                                    <option value="{{ $id }}" {{ in_array($id, old('informations', [])) ? 'selected' : '' }}>{{ $informations }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('informations'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('informations') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.process.fields.informations_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="entities">{{ trans('cruds.process.fields.applications') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all"
                                      style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all"
                                      style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}"
                                    name="applications[]" id="applications" multiple>
                                @foreach($applications as $id => $application)
                                    <option value="{{ $id }}" {{ in_array($id, old('applications', [])) ? 'selected' : '' }}>{{ $application }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('applications'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('applications') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.process.fields.applications_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.processes.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
