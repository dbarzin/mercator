@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.application-blocks.update", [$applicationBlock->id]) }}"
          enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.applicationBlock.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.applicationBlock.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', $applicationBlock->name) }}" required maxlength="64"
                           autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationBlock.fields.name_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended2"
                           for="description">{{ trans('cruds.applicationBlock.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description"
                              id="description">{!! old('description', $applicationBlock->description) !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationBlock.fields.description_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended2"
                           for="responsible">{{ trans('cruds.applicationBlock.fields.responsible') }}</label>
                    <input class="form-control {{ $errors->has('responsible') ? 'is-invalid' : '' }}" type="text"
                           name="responsible" id="responsible"
                           value="{{ old('responsible', $applicationBlock->responsible) }}">
                    @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationBlock.fields.responsible_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended2"
                           for="applications">{{ trans('cruds.applicationBlock.fields.applications') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}"
                            name="linkToApplications[]" id="linkToApplications" multiple>
                        @foreach($applications as $id => $name)
                            <option value="{{ $id }}" {{ (in_array($id, old('applications', [])) || $applicationBlock->applications->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('applications'))
                        <span class="text-danger">{{ $errors->first('applications') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.applicationBlock.fields.applications_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.application-blocks.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
