@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.flux.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.fluxes.update", [$flux->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.flux.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $flux->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.flux.fields.name_helper') }}</span>
	    </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.flux.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $flux->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.flux.fields.description_helper') }}</span>
            </div>

          <div class="row">
	    <div class="col-sm">
                <div class="form-group">
                    <label class="recommended">Source</label><br>
                    <label for="application_source_id">{{ trans('cruds.flux.fields.application_source') }}</label>
                    <select class="form-control select2 {{ $errors->has('application_source') ? 'is-invalid' : '' }}" name="application_source_id" id="application_source_id">
                        @foreach($application_sources as $id => $application_source)
                            <option value="{{ $id }}" {{ ($flux->application_source ? $flux->application_source->id : old('application_source_id')) == $id ? 'selected' : '' }}>{{ $application_source }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('application_source'))
                        <div class="invalid-feedback">
                            {{ $errors->first('application_source') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.application_source_helper') }}</span>
                </div>
                @if (auth()->user()->granularity>=2)
                <div class="form-group">
                    <label for="service_source_id">{{ trans('cruds.flux.fields.service_source') }}</label>
                    <select class="form-control select2 {{ $errors->has('service_source') ? 'is-invalid' : '' }}" name="service_source_id" id="service_source_id">
                        @foreach($service_sources as $id => $service_source)
                            <option value="{{ $id }}" {{ ($flux->service_source ? $flux->service_source->id : old('service_source_id')) == $id ? 'selected' : '' }}>{{ $service_source }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('service_source'))
                        <div class="invalid-feedback">
                            {{ $errors->first('service_source') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.service_source_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="module_source_id">{{ trans('cruds.flux.fields.module_source') }}</label>
                    <select class="form-control select2 {{ $errors->has('module_source') ? 'is-invalid' : '' }}" name="module_source_id" id="module_source_id">
                        @foreach($module_sources as $id => $module_source)
                            <option value="{{ $id }}" {{ ($flux->module_source ? $flux->module_source->id : old('module_source_id')) == $id ? 'selected' : '' }}>{{ $module_source }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('module_source'))
                        <div class="invalid-feedback">
                            {{ $errors->first('module_source') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.module_source_helper') }}</span>
                </div>
                @endif
                <div class="form-group">
                    <label for="database_source_id">{{ trans('cruds.flux.fields.database_source') }}</label>
                    <select class="form-control select2 {{ $errors->has('database_source') ? 'is-invalid' : '' }}" name="database_source_id" id="database_source_id">
                        @foreach($database_sources as $id => $database_source)
                            <option value="{{ $id }}" {{ ($flux->database_source ? $flux->database_source->id : old('database_source_id')) == $id ? 'selected' : '' }}>{{ $database_source }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('database_source'))
                        <div class="invalid-feedback">
                            {{ $errors->first('database_source') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.database_source_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
		<div class="form-group">
                    <label class="recommended">Destination</label><br>
                    <label for="application_dest_id">{{ trans('cruds.flux.fields.application_dest') }}</label>
                    <select class="form-control select2 {{ $errors->has('application_dest') ? 'is-invalid' : '' }}" name="application_dest_id" id="application_dest_id">
                        @foreach($application_dests as $id => $application_dest)
                            <option value="{{ $id }}" {{ ($flux->application_dest ? $flux->application_dest->id : old('application_dest_id')) == $id ? 'selected' : '' }}>{{ $application_dest }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('application_dest'))
                        <div class="invalid-feedback">
                            {{ $errors->first('application_dest') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.application_dest_helper') }}</span>
                </div>
                @if (auth()->user()->granularity>=2)
                <div class="form-group">
                    <label for="service_dest_id">{{ trans('cruds.flux.fields.service_dest') }}</label>
                    <select class="form-control select2 {{ $errors->has('service_dest') ? 'is-invalid' : '' }}" name="service_dest_id" id="service_dest_id">
                        @foreach($service_dests as $id => $service_dest)
                            <option value="{{ $id }}" {{ ($flux->service_dest ? $flux->service_dest->id : old('service_dest_id')) == $id ? 'selected' : '' }}>{{ $service_dest }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('service_dest'))
                        <div class="invalid-feedback">
                            {{ $errors->first('service_dest') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.service_dest_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="module_dest_id">{{ trans('cruds.flux.fields.module_dest') }}</label>
                    <select class="form-control select2 {{ $errors->has('module_dest') ? 'is-invalid' : '' }}" name="module_dest_id" id="module_dest_id">
                        @foreach($module_dests as $id => $module_dest)
                            <option value="{{ $id }}" {{ ($flux->module_dest ? $flux->module_dest->id : old('module_dest_id')) == $id ? 'selected' : '' }}>{{ $module_dest }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('module_dest'))
                        <div class="invalid-feedback">
                            {{ $errors->first('module_dest') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.module_dest_helper') }}</span>
                </div>
                @endif
                <div class="form-group">
                    <label for="database_dest_id">{{ trans('cruds.flux.fields.database_dest') }}</label>
                    <select class="form-control select2 {{ $errors->has('database_dest') ? 'is-invalid' : '' }}" name="database_dest_id" id="database_dest_id">
                        @foreach($database_dests as $id => $database_dest)
                            <option value="{{ $id }}" {{ ($flux->database_dest ? $flux->database_dest->id : old('database_dest_id')) == $id ? 'selected' : '' }}>{{ $database_dest }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('database_dest'))
                        <div class="invalid-feedback">
                            {{ $errors->first('database_dest') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.flux.fields.database_dest_helper') }}</span>
                </div>
            </div>
            </div>

            <div class="form-group">
                <label for="crypted">{{ trans('cruds.flux.fields.crypted') }}</label>
                <select class="form-control select2 {{ $errors->has('crypted') ? 'is-invalid' : '' }}" name="crypted" id="crypted">
                    <option value=""></option>
                    <option value="1" {{ $flux->crypted  == true ? 'selected' : '' }}>Oui</option>
                    <option value="0" {{ $flux->crypted  == false ? 'selected' : '' }}>Non</option>
                </select>
                @if($errors->has('crypted'))
                    <div class="invalid-feedback">
                        {{ $errors->first('crypted') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.flux.fields.crypted_helper') }}</span>
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
    });

    $('#application_source_id').on("select2:selecting", function(e) { 
       $('#service_source_id').val(null).trigger('change');
       $('#module_source_id').val(null).trigger('change');
       $('#database_source_id').val(null).trigger('change');
    });
    
    $('#service_source_id').on("select2:selecting", function(e) { 
       $('#application_source_id').val(null).trigger('change');
       $('#module_source_id').val(null).trigger('change');
       $('#database_source_id').val(null).trigger('change');
    });

    $('#module_source_id').on("select2:selecting", function(e) { 
       $('#application_source_id').val(null).trigger('change');
       $('#service_source_id').val(null).trigger('change');
       $('#database_source_id').val(null).trigger('change');
    });

    $('#database_source_id').on("select2:selecting", function(e) { 
       $('#application_source_id').val(null).trigger('change');
       $('#service_source_id').val(null).trigger('change');
       $('#module_source_id').val(null).trigger('change');
    });


    $('#application_dest_id').on("select2:selecting", function(e) { 
       $('#service_dest_id').val(null).trigger('change');
       $('#module_dest_id').val(null).trigger('change');
       $('#database_dest_id').val(null).trigger('change');
    });
    
    $('#service_dest_id').on("select2:selecting", function(e) { 
       $('#application_dest_id').val(null).trigger('change');
       $('#module_dest_id').val(null).trigger('change');
       $('#database_dest_id').val(null).trigger('change');
    });

    $('#module_dest_id').on("select2:selecting", function(e) { 
       $('#application_dest_id').val(null).trigger('change');
       $('#service_dest_id').val(null).trigger('change');
       $('#database_dest_id').val(null).trigger('change');
    });

    $('#database_dest_id').on("select2:selecting", function(e) { 
       $('#application_dest_id').val(null).trigger('change');
       $('#service_dest_id').val(null).trigger('change');
       $('#module_dest_id').val(null).trigger('change');
    });

});

</script>
@endsection

