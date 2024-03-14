@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.router.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.routers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.router.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.router.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.router.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.router.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="ip_addresses">{{ trans('cruds.router.fields.ip_addresses') }}</label>
                <textarea class="form-control {{ $errors->has('ip_addresses') ? 'is-invalid' : '' }}" name="ip_addresses" id="ip_addresses">{!! old('ip_addresses') !!}</textarea>
                @if($errors->has('ip_addresses'))
                    <div class="invalid-feedback">
                        {{ $errors->first('ip_addresses') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.router.fields.ip_addresses_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rules">{{ trans('cruds.router.fields.rules') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('rules') ? 'is-invalid' : '' }}" name="rules" id="rules">{!! old('rules') !!}</textarea>
                @if($errors->has('rules'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rules') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.router.fields.rules_helper') }}</span>
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
