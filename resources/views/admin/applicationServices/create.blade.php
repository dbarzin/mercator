@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.application-services.store") }}" enctype="multipart/form-data">
        @csrf

        <div class="card">
            <div class="card-header">
                {{ trans('global.create') }} {{ trans('cruds.applicationService.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="label-required" for="name">{{ trans('cruds.applicationService.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', '') }}" maxlength="64" required autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationService.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-2"
                           for="description">{{ trans('cruds.applicationService.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description" id="description">{!! old('description') !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationService.fields.description_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="exposition">{{ trans('cruds.applicationService.fields.exposition') }}</label>
                    <select class="form-control select2-free {{ $errors->has('exposition') ? 'is-invalid' : '' }}"
                            name="exposition" id="exposition">
                        @if (!$exposition_list->contains(old('exposition')))
                            <option> {{ old('exposition') }}</option>
                        @endif
                        @foreach($exposition_list as $t)
                            <option {{ old('exposition') == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('exposition'))
                        <div class="invalid-feedback">
                            {{ $errors->first('exposition') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationService.fields.exposition_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="label-maturity-2"
                           for="applications">{{ trans('cruds.applicationService.fields.applications') }}</label>
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
                    <span class="help-block">{{ trans('cruds.applicationService.fields.applications_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="modules">{{ trans('cruds.applicationService.fields.modules') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('modules') ? 'is-invalid' : '' }}"
                            name="modules[]" id="modules" multiple>
                        @foreach($modules as $id => $name)
                            <option value="{{ $id }}" {{ in_array($id, old('modules', [])) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('modules'))
                        <div class="invalid-feedback">
                            {{ $errors->first('modules') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationService.fields.modules_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.application-services.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
