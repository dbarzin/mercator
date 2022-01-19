@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.physicalSwitch.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.physical-switches.update", [$physicalSwitch->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.physicalSwitch.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $physicalSwitch->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalSwitch.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description" class="recommended">{{ trans('cruds.physicalSwitch.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $physicalSwitch->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalSwitch.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="type" class="recommended">{{ trans('cruds.physicalSwitch.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', $physicalSwitch->type) }}">
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalSwitch.fields.type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="site_id" class="recommended">{{ trans('cruds.physicalSwitch.fields.site') }}</label>
                <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                    @foreach($sites as $id => $site)
                        <option value="{{ $id }}" {{ ($physicalSwitch->site ? $physicalSwitch->site->id : old('site_id')) == $id ? 'selected' : '' }}>{{ $site }}</option>
                    @endforeach
                </select>
                @if($errors->has('site'))
                    <div class="invalid-feedback">
                        {{ $errors->first('site') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalSwitch.fields.site_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="building_id" class="recommended">{{ trans('cruds.physicalSwitch.fields.building') }}</label>
                <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}" name="building_id" id="building_id">
                    @foreach($buildings as $id => $building)
                        <option value="{{ $id }}" {{ ($physicalSwitch->building ? $physicalSwitch->building->id : old('building_id')) == $id ? 'selected' : '' }}>{{ $building }}</option>
                    @endforeach
                </select>
                @if($errors->has('building'))
                    <div class="invalid-feedback">
                        {{ $errors->first('building') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalSwitch.fields.building_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bay_id" class="recommended">{{ trans('cruds.physicalSwitch.fields.bay') }}</label>
                <select class="form-control select2 {{ $errors->has('bay') ? 'is-invalid' : '' }}" name="bay_id" id="bay_id">
                    @foreach($bays as $id => $bay)
                        <option value="{{ $id }}" {{ ($physicalSwitch->bay ? $physicalSwitch->bay->id : old('bay_id')) == $id ? 'selected' : '' }}>{{ $bay }}</option>
                    @endforeach
                </select>
                @if($errors->has('bay'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bay') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalSwitch.fields.bay_helper') }}</span>
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