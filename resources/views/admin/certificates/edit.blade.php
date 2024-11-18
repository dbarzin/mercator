@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.certificates.update", [$certificate->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.certificate.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.certificate.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $certificate->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.certificate.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="type">{{ trans('cruds.certificate.fields.type') }}</label>
                <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    @if (!$type_list->contains(old('type')))
                        <option> {{ old('type') }}</option>'
                    @endif
                    @foreach($type_list as $t)
                        <option {{ (old('users') ? old('users') : $certificate->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                    @endforeach
                </select>
                @if($errors->has('types'))
                    <div class="invalid-feedback">
                        {{ $errors->first('types') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.certificate.fields.type_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.certificate.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $certificate->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.certificate.fields.description_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-sm">
                     <div class="form-group">
                        <label for="logical_servers">{{ trans('cruds.certificate.fields.start_validity') }}</label>
                        <input class="form-control date" type="text" name="start_validity" id="start_validity" value="{{ old('start_validity', $certificate->start_validity) }}">
                        <span class="help-block">{{ trans('cruds.certificate.fields.start_validity_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                     <div class="form-group">
                        <label for="logical_servers">{{ trans('cruds.certificate.fields.end_validity') }}</label>
                        <input class="date form-control" type="text" id="end_validity" name="end_validity" value="{{ old('end_validity', $certificate->end_validity) }}">
                        <span class="help-block">{{ trans('cruds.certificate.fields.end_validity_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                     <div class="form-group">
                        <label for="logical_servers">{{ trans('cruds.certificate.fields.status') }}</label>
                        <select class="form-control select2 {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status">
                            <option></option>
                            <option value="0" {{ old('status', $certificate->status)==0 ? 'selected' : '' }}>{{ trans('cruds.certificate.fields.status_good') }}</option>
                            <option value="1" {{ old('status', $certificate->status)==1 ? 'selected' : '' }}>{{ trans('cruds.certificate.fields.status_revoked') }}</option>
                            <option value="2" {{ old('status', $certificate->status)==2 ? 'selected' : '' }}>{{ trans('cruds.certificate.fields.status_unknown') }}</option>
                        </select>
                        <span class="help-block">{{ trans('cruds.certificate.fields.status_helper') }}</span>
                    </div>
                </div>
            </div>


            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="logical_servers">{{ trans('cruds.certificate.fields.logical_servers') }}</label>
                        <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="logical_servers[]" id="logical_servers" multiple>
                            @foreach($logical_servers as $id => $logical_server)
                                <option value="{{ $id }}" {{ (in_array($id, old('logical_servers', [])) || $certificate->logical_servers->contains($id)) ? 'selected' : '' }}>{{ $logical_server }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('logical_servers'))
                            <div class="invalid-feedback">
                                {{ $errors->first('logical_servers') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.certificate.fields.logical_servers_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="applications">{{ trans('cruds.certificate.fields.applications') }}</label>
                        <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                            @foreach($applications as $id => $application)
                                <option value="{{ $id }}" {{ (in_array($id, old('applications', [])) || $certificate->applications->contains($id)) ? 'selected' : '' }}>{{ $application }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('applications'))
                            <div class="invalid-feedback">
                                {{ $errors->first('applications') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.certificate.fields.applications_helper') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.certificates.index') }}">
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

$(document).ready(function() {
  $(".select2-free").select2({
        placeholder: "{{ trans('global.pleaseSelect') }}",
        allowClear: true,
        tags: true
    })
  });
</script>
@endsection
