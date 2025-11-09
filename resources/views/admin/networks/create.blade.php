@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.networks.store") }}" enctype="multipart/form-data">
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.network.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.network.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', '') }}" required autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.network.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1"
                           for="description">{{ trans('cruds.network.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description" id="description">{!! old('description') !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.network.fields.description_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="protocol_type">{{ trans('cruds.network.fields.protocol_type') }}</label>
                    <input class="form-control {{ $errors->has('protocol_type') ? 'is-invalid' : '' }}" type="text"
                           name="protocol_type" id="protocol_type" value="{{ old('protocol_type', '') }}">
                    @if($errors->has('protocol_type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('protocol_type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.network.fields.protocol_type_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1"
                           for="responsible">{{ trans('cruds.network.fields.responsible') }}</label>
                    <input class="form-control {{ $errors->has('responsible') ? 'is-invalid' : '' }}" type="text"
                           name="responsible" id="responsible" value="{{ old('responsible', '') }}">
                    @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.network.fields.responsible_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1"
                           for="responsible_sec">{{ trans('cruds.network.fields.responsible_sec') }}</label>
                    <input class="form-control {{ $errors->has('responsible_sec') ? 'is-invalid' : '' }}" type="text"
                           name="responsible_sec" id="responsible_sec" value="{{ old('responsible_sec', '') }}">
                    @if($errors->has('responsible_sec'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible_sec') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.network.fields.responsible_sec_helper') }}</span>
                </div>


                <div class="row">
                    <div class="col-sm">

                        <div class="form-group">


                            <table cellspacing="5" cellpadding="5" border="0" width='100%'>
                                <tr>
                                    <td width='20%'>
                                        <label class="label-maturity-1"
                                               for="security_need">{{ trans('cruds.information.fields.security_need') }}
                                        </label>
                                    </td>
                                    <td align="right" width="10">
                                        <label for="security_need">{{ trans('global.confidentiality_short') }}</label>
                                    </td>
                                    <td width="120">
                                        <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}"
                                                name="security_need_c" id="security_need_c">
                                            <option value="-1"></option>
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
                                            <option value="-1"></option>
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
                                            <option value="-1"></option>
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
                                            <option value="-1"></option>
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
                            <span class="help-block">{{ trans('cruds.network.fields.security_need_helper') }}</span>
                        </div>

                    </div>
                    <div class="col-sm">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.networks.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
