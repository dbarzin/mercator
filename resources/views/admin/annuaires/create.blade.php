@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.annuaires.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.annuaire.title_singular') }}
        </div>
        <div class="card-body">
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.annuaire.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" maxlength="32" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.annuaire.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.annuaire.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.annuaire.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="solution">{{ trans('cruds.annuaire.fields.solution') }}</label>
                <input class="form-control {{ $errors->has('solution') ? 'is-invalid' : '' }}" type="text" name="solution" id="solution" value="{{ old('solution', '') }}">
                @if($errors->has('solution'))
                    <div class="invalid-feedback">
                        {{ $errors->first('solution') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.annuaire.fields.solution_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="zone_admin_id">{{ trans('cruds.annuaire.fields.zone_admin') }}</label>
                <select class="form-control select2 {{ $errors->has('zone_admin') ? 'is-invalid' : '' }}" name="zone_admin_id" id="zone_admin_id">
                    @foreach($zone_admins as $id => $zone_admin)
                        <option value="{{ $id }}" {{ old('zone_admin_id') == $id ? 'selected' : '' }}>{{ $zone_admin }}</option>
                    @endforeach
                </select>
                @if($errors->has('zone_admin'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zone_admin') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.annuaire.fields.zone_admin_helper') }}</span>
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
