@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.macro-processuses.update", [$macroProcessus->id]) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.macroProcessus.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.macroProcessus.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', $macroProcessus->name) }}" maxlength="64" required
                           autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.macroProcessus.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="recommended"
                           for="description">{{ trans('cruds.macroProcessus.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description"
                              id="description">{!! old('description', $macroProcessus->description) !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.macroProcessus.fields.description_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="recommended"
                           for="io_elements">{{ trans('cruds.macroProcessus.fields.io_elements') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('io_elements') ? 'is-invalid' : '' }}"
                              name="io_elements"
                              id="io_elements">{!! old('io_elements', $macroProcessus->io_elements) !!}</textarea>
                    @if($errors->has('io_elements'))
                        <div class="invalid-feedback">
                            {{ $errors->first('io_elements') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.macroProcessus.fields.io_elements_helper') }}</span>
                </div>


                <div class="form-group">
                    <table cellspacing="5" cellpadding="5" border="0">
                        <tr>
                            <td width="140" nowrap>
                                <label class="recommended"
                                       for="security_need">{{ trans('cruds.macroProcessus.fields.security_need') }}</label>
                            </td>
                            <td align="right" width="10">
                                <label for="security_need">{{ trans("global.confidentiality_short") }}</label>
                            </td>
                            <td width="120">
                                <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}"
                                        name="security_need_c" id="security_need_c">
                                    <option value="-1" {{ ($macroProcessus->security_need_c ? $macroProcessus->security_need_c : old('security_need_c')) == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ ($macroProcessus->security_need_c ? $macroProcessus->security_need_c : old('security_need_c')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ ($macroProcessus->security_need_c ? $macroProcessus->security_need_c : old('security_need_c')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ ($macroProcessus->security_need_c ? $macroProcessus->security_need_c : old('security_need_c')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ ($macroProcessus->security_need_c ? $macroProcessus->security_need_c : old('security_need_c')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ ($macroProcessus->security_need_c ? $macroProcessus->security_need_c : old('security_need_c')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
                            <td align="right" width="10">
                                <label for="security_need">{{ trans("global.integrity_short") }}</label>
                            </td>
                            <td width="120">
                                <select class="form-control select2 risk {{ $errors->has('security_need_i') ? 'is-invalid' : '' }}"
                                        name="security_need_i" id="security_need_i">
                                    <option value="-1" {{ ($macroProcessus->security_need_i ? $macroProcessus->security_need_i : old('security_need_i')) == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ ($macroProcessus->security_need_i ? $macroProcessus->security_need_i : old('security_need_i')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ ($macroProcessus->security_need_i ? $macroProcessus->security_need_i : old('security_need_i')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ ($macroProcessus->security_need_i ? $macroProcessus->security_need_i : old('security_need_i')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ ($macroProcessus->security_need_i ? $macroProcessus->security_need_i : old('security_need_i')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ ($macroProcessus->security_need_i ? $macroProcessus->security_need_i : old('security_need_i')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
                            <td align="right" width="10">
                                <label for="security_need">{{ trans("global.availability_short") }}</label>
                            </td>
                            <td width="120">
                                <select class="form-control select2 risk {{ $errors->has('security_need_a') ? 'is-invalid' : '' }}"
                                        name="security_need_a" id="security_need_a">
                                    <option value="-1" {{ ($macroProcessus->security_need_a ? $macroProcessus->security_need_a : old('security_need_a')) == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ ($macroProcessus->security_need_a ? $macroProcessus->security_need_a : old('security_need_a')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ ($macroProcessus->security_need_a ? $macroProcessus->security_need_a : old('security_need_a')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ ($macroProcessus->security_need_a ? $macroProcessus->security_need_a : old('security_need_a')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ ($macroProcessus->security_need_a ? $macroProcessus->security_need_a : old('security_need_a')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ ($macroProcessus->security_need_a ? $macroProcessus->security_need_a : old('security_need_a')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
                            <td align="right" width="10">
                                <label for="security_need">{{ trans("global.tracability_short") }}</label>
                            </td>
                            <td width="120">
                                <select class="form-control select2 risk {{ $errors->has('security_need_t') ? 'is-invalid' : '' }}"
                                        name="security_need_t" id="security_need_t">
                                    <option value="-1" {{ ($macroProcessus->security_need_t ? $macroProcessus->security_need_t : old('security_need_t')) == -1 ? 'selected' : '' }}></option>
                                    <option value="0" {{ ($macroProcessus->security_need_t ? $macroProcessus->security_need_t : old('security_need_t')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                    <option value="1" {{ ($macroProcessus->security_need_t ? $macroProcessus->security_need_t : old('security_need_t')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                    <option value="2" {{ ($macroProcessus->security_need_t ? $macroProcessus->security_need_t : old('security_need_t')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                    <option value="3" {{ ($macroProcessus->security_need_t ? $macroProcessus->security_need_t : old('security_need_t')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                    <option value="4" {{ ($macroProcessus->security_need_t ? $macroProcessus->security_need_t : old('security_need_t')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                </select>
                            </td>
                            @if (config('mercator-config.parameters.security_need_auth'))
                                <td align="right" width="10">
                                    <label for="security_need">{{ trans('global.authenticity_short') }}</label>
                                </td>
                                <td width="120">
                                    <select class="form-control select2 risk{{ $errors->has('security_need_auth') ? 'is-invalid' : '' }}"
                                            name="security_need_auth" id="security_need_auth">
                                        <option value="-1"></option>
                                        <option value="0" {{ ($macroProcessus->security_need_auth ? $macroProcessus->security_need_auth : old('security_need_auth')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ ($macroProcessus->security_need_auth ? $macroProcessus->security_need_auth : old('security_need_auth')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ ($macroProcessus->security_need_auth ? $macroProcessus->security_need_auth : old('security_need_auth')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ ($macroProcessus->security_need_auth ? $macroProcessus->security_need_auth : old('security_need_auth')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ ($macroProcessus->security_need_auth ? $macroProcessus->security_need_auth : old('security_need_auth')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
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
                    <span class="help-block">{{ trans('cruds.macroProcessus.fields.security_need_helper') }}</span>
                </div>


                <div class="form-group">
                    <label class="recommended" for="owner">{{ trans('cruds.macroProcessus.fields.owner') }}</label>
                    <select class="form-control select2-free {{ $errors->has('owner') ? 'is-invalid' : '' }}"
                            name="owner" id="owner">
                        @if (!$owner_list->contains(old('owner')))
                            <option> {{ old('owner') }}</option>
                        @endif
                        @foreach($owner_list as $t)
                            <option {{ (old('owner') ? old('owner') : $macroProcessus->owner) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('owner'))
                        <div class="invalid-feedback">
                            {{ $errors->first('owner') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.macroProcessus.fields.owner_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="processes">{{ trans('cruds.macroProcessus.fields.processes') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}"
                            name="processes[]" id="processes" multiple>
                        @foreach($processes as $id => $processes)
                            <option value="{{ $id }}" {{ (in_array($id, old('processes', [])) || $macroProcessus->processes->contains($id)) ? 'selected' : '' }}>{{ $processes }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('processes'))
                        <div class="invalid-feedback">
                            {{ $errors->first('processes') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.macroProcessus.fields.processes_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.macro-processuses.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
