@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.buildings.update", [$building->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.building.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.building.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $building->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.building.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.building.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $building->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.building.fields.description_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="attributes">{{ trans('cruds.building.fields.attributes') }}</label>
                <select class="form-control select2-free {{ $errors->has('patching_group') ? 'is-invalid' : '' }}" name="attributes[]" id="attributes[]" multiple>
                    @foreach($attributes_list as $a)
                        <option {{ ( (old('attributes')!=null) && in_array($a,old('attributes'))) || in_array($a, explode(' ',$building->attributes)) ? 'selected' : '' }}>{{$a}}</option>
                    @endforeach
                </select>
                @if($errors->has('attributes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attributes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.building.fields.attributes_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="site_id">{{ trans('cruds.building.fields.site') }}</label>
                <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                    <option>{{ trans('global.pleaseSelect') }}</option>
                    @foreach($sites as $id => $site)
                        <option value="{{ $id }}" {{ ($building->site ? $building->site->id : old('site_id')) == $id ? 'selected' : '' }}>{{ $site }}</option>
                    @endforeach
                </select>
                @if($errors->has('site'))
                    <div class="invalid-feedback">
                        {{ $errors->first('site') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.building.fields.site_helper') }}</span>
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
