@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.activity.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.activities.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.activity.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.activity.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.description_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="responsible">{{ trans('cruds.activity.fields.responsible') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsible" id="responsible">{!! old('responsible') !!}</textarea>
                @if($errors->has('responsible'))
                    <div class="invalid-feedback">
                        {{ $errors->first('responsible') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.responsible_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="purpose">{{ trans('cruds.activity.fields.purpose') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('purpose') ? 'is-invalid' : '' }}" name="purpose" id="purpose">{!! old('purpose') !!}</textarea>
                @if($errors->has('purpose'))
                    <div class="invalid-feedback">
                        {{ $errors->first('purpose') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.purpose_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="categories">{{ trans('cruds.activity.fields.categories') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('categories') ? 'is-invalid' : '' }}" name="categories" id="categories">{!! old('categories') !!}</textarea>
                @if($errors->has('categories'))
                    <div class="invalid-feedback">
                        {{ $errors->first('categories') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.categories_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="recipients">{{ trans('cruds.activity.fields.recipients') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('recipients') ? 'is-invalid' : '' }}" name="recipients" id="recipients">{!! old('recipients') !!}</textarea>
                @if($errors->has('recipients'))
                    <div class="invalid-feedback">
                        {{ $errors->first('recipients') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.recipients_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="transfert">{{ trans('cruds.activity.fields.transfert') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('transfert') ? 'is-invalid' : '' }}" name="transfert" id="transfert">{!! old('transfert') !!}</textarea>
                @if($errors->has('transfert'))
                    <div class="invalid-feedback">
                        {{ $errors->first('transfert') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.transfert_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="retention">{{ trans('cruds.activity.fields.retention') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('retention') ? 'is-invalid' : '' }}" name="retention" id="retention">{!! old('retention') !!}</textarea>
                @if($errors->has('retention'))
                    <div class="invalid-feedback">
                        {{ $errors->first('retention') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.retention_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="controls">{{ trans('cruds.activity.fields.controls') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('controls') ? 'is-invalid' : '' }}" name="controls" id="controls">{!! old('controls') !!}</textarea>
                @if($errors->has('controls'))
                    <div class="invalid-feedback">
                        {{ $errors->first('controls') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.controls_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-6">

                    <div class="form-group">
                        <label for="operations">{{ trans('cruds.activity.fields.processes') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
                            @foreach($processes as $id => $processes)
                                <option value="{{ $id }}" {{ in_array($id, old('processes', [])) ? 'selected' : '' }}>{{ $processes }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('processes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('processes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.processes_helper') }}</span>
                    </div>
                
                </div>

                <dic class="col-6">

                    <div class="form-group">
                        <label for="operations">{{ trans('cruds.activity.fields.operations') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('operations') ? 'is-invalid' : '' }}" name="operations[]" id="operations" multiple>
                            @foreach($operations as $id => $operations)
                                <option value="{{ $id }}" {{ in_array($id, old('operations', [])) ? 'selected' : '' }}>{{ $operations }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('operations'))
                            <div class="invalid-feedback">
                                {{ $errors->first('operations') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.operations_helper') }}</span>
                    </div>
                </dic>
            </div>


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
<script>
$(document).ready(function () {
  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: []
      }
    );
  }
});
</script></script>

@endsection