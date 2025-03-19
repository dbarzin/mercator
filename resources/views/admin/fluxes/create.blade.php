@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.fluxes.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.flux.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.flux.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.flux.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.flux.fields.nature') }}</label>
                <select class="form-control select2-free {{ $errors->has('nature') ? 'is-invalid' : '' }}" name="nature" id="nature">
                    @if (!$nature_list->contains(old('nature')))
                        <option> {{ old('nature') }}</option>'
                    @endif
                    @foreach($nature_list as $nature)
                        <option {{ old('nature') == $nature ? 'selected' : '' }}>{{$nature}}</option>
                    @endforeach
                </select>
                @if($errors->has('nature'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nature') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.flux.fields.nature_helper') }}</span>
            </div>
            <div class="form-group">
        		<label class="recommended" for="description">{{ trans('cruds.flux.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <!-- <span class="help-block">{{ trans('cruds.flux.fields.description_helper') }}</span> -->
            </div>

          <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.flux.fields.source') }}</label>
                    <select class="form-control select2 {{ $errors->has('dest_id') ? 'is-invalid' : '' }}" name="src_id" id="src_id">
                        <option></option>
                        @foreach($items as $id => $name)
                            <option value="{{ $id }}" {{ old('src_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('src_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('src_id') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.source_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.flux.fields.destination') }}</label>
                    <select class="form-control select2 {{ $errors->has('dest_id') ? 'is-invalid' : '' }}" name="dest_id" id="dest_id">
                        <option></option>
                        @foreach($items as $id => $name)
                            <option value="{{ $id }}" {{ old('dest_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('dest_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('dest_id') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.destination_helper') }}</span>
                </div>
            </div>
        </div>

            <div class="form-check">
                <label for="crypted">{{ trans('cruds.flux.fields.crypted') }}</label>
                <div class="form-switch">
                  <input class="form-check-input" type="checkbox" id="crypted" name="crypted" value="1" {{ old('crypted') ? 'checked' : '' }}>
                  <label class="form-check-label" for="crypted">{{ trans('cruds.flux.fields.crypted_helper') }}</label>
                </div>
            </div>

            <div class="form-check">
                <label for="crypted">{{ trans('cruds.flux.fields.bidirectional') }}</label>
                <div class="form-switch">
                  <input class="form-check-input" type="checkbox" id="bidirectional" name="bidirectional" value="1" {{ old('bidirectional') ? 'checked' : '' }}>
                  <label class="form-check-label" for="bidirectional">{{ trans('cruds.flux.fields.bidirectional_helper') }}</label>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.fluxes.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

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

});

</script>
@endsection
