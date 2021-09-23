@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.physicalServer.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.physical-servers.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.physicalServer.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="type">{{ trans('cruds.physicalServer.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', '') }}">
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.type_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="description">{{ trans('cruds.physicalServer.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.description_helper') }}</span>
            </div>


            <div class="form-group">
                <label for="configuration">{{ trans('cruds.physicalServer.fields.configuration') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('configuration') ? 'is-invalid' : '' }}" name="configuration" id="configuration">{!! old('configuration') !!}</textarea>
                @if($errors->has('configuration'))
                    <div class="invalid-feedback">
                        {{ $errors->first('configuration') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.configuration_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="responsible">{{ trans('cruds.physicalServer.fields.responsible') }}</label>
                <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsible" id="responsible">
                    @if (!$responsible_list->contains(old('responsible')))
                        <option> {{ old('responsible') }}</option>'
                    @endif
                    @foreach($responsible_list as $t)
                        <option {{ old('responsible') == $t ? 'selected' : '' }}>{{$t}}</option>
                    @endforeach
                </select>
                @if($errors->has('responsible'))
                    <div class="invalid-feedback">
                        {{ $errors->first('responsible') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.responsible_helper') }}</span>
            </div>
            
            <div class="form-group">
                <label for="site_id">{{ trans('cruds.physicalServer.fields.site') }}</label>
                <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                    @foreach($sites as $id => $site)
                        <option value="{{ $id }}" {{ old('site_id') == $id ? 'selected' : '' }}>{{ $site }}</option>
                    @endforeach
                </select>
                @if($errors->has('site'))
                    <div class="invalid-feedback">
                        {{ $errors->first('site') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.site_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="building_id">{{ trans('cruds.physicalServer.fields.building') }}</label>
                <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}" name="building_id" id="building_id">
                    @foreach($buildings as $id => $building)
                        <option value="{{ $id }}" {{ old('building_id') == $id ? 'selected' : '' }}>{{ $building }}</option>
                    @endforeach
                </select>
                @if($errors->has('building'))
                    <div class="invalid-feedback">
                        {{ $errors->first('building') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.building_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bay_id">{{ trans('cruds.physicalServer.fields.bay') }}</label>
                <select class="form-control select2 {{ $errors->has('bay') ? 'is-invalid' : '' }}" name="bay_id" id="bay_id">
                    @foreach($bays as $id => $bay)
                        <option value="{{ $id }}" {{ old('bay_id') == $id ? 'selected' : '' }}>{{ $bay }}</option>
                    @endforeach
                </select>
                @if($errors->has('bay'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bay') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.bay_helper') }}</span>
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