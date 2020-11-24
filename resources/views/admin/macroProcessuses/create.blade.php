@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.macroProcessus.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.macro-processuses.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.macroProcessus.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.macroProcessus.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.macroProcessus.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.macroProcessus.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="io_elements">{{ trans('cruds.macroProcessus.fields.io_elements') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('io_elements') ? 'is-invalid' : '' }}" name="io_elements" id="io_elements">{!! old('io_elements') !!}</textarea>
                @if($errors->has('io_elements'))
                    <div class="invalid-feedback">
                        {{ $errors->first('io_elements') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.macroProcessus.fields.io_elements_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="security_need">{{ trans('cruds.macroProcessus.fields.security_need') }}</label>
                <select class="form-control select2 {{ $errors->has('security_need') ? 'is-invalid' : '' }}" name="security_need" id="security_need">
                    <option value="0"></option>
                    <option value="1" {{ old('security_need') == 1 ? 'selected' : '' }}>Public</option>
                    <option value="2" {{ old('security_need') == 2 ? 'selected' : '' }}>Internal</option>
                    <option value="3" {{ old('security_need') == 3 ? 'selected' : '' }}>Confidential</option>
                    <option value="4" {{ old('security_need') == 4 ? 'selected' : '' }}>Secret</option>
                </select>                
                @if($errors->has('security_need'))
                    <div class="invalid-feedback">
                        {{ $errors->first('security_need') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.macroProcessus.fields.security_need_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="owner">{{ trans('cruds.macroProcessus.fields.owner') }}</label>
                <select class="form-control select2-free {{ $errors->has('owner') ? 'is-invalid' : '' }}" name="owner" id="owner">
                    @if (!$owner_list->contains(old('owner')))
                        <option> {{ old('owner') }}</option>'
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
                <span class="help-block">{{ trans('cruds.macroProcessus.fields.owner_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="processes">{{ trans('cruds.macroProcessus.fields.processes') }}</label>
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
                <span class="help-block">{{ trans('cruds.macroProcessus.fields.processes_helper') }}</span>
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
$(document).ready(function () {

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

});
</script>
@endsection