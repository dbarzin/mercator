@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.network.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.networks.update", [$network->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.network.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $network->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.network.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.network.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $network->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.network.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="protocol_type">{{ trans('cruds.network.fields.protocol_type') }}</label>
                <input class="form-control {{ $errors->has('protocol_type') ? 'is-invalid' : '' }}" type="text" name="protocol_type" id="protocol_type" value="{{ old('protocol_type', $network->protocol_type) }}">
                @if($errors->has('protocol_type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('protocol_type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.network.fields.protocol_type_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="responsible">{{ trans('cruds.network.fields.responsible') }}</label>
                <input class="form-control {{ $errors->has('responsible') ? 'is-invalid' : '' }}" type="text" name="responsible" id="responsible" value="{{ old('responsible', $network->responsible) }}">
                @if($errors->has('responsible'))
                    <div class="invalid-feedback">
                        {{ $errors->first('responsible') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.network.fields.responsible_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="responsible_sec">{{ trans('cruds.network.fields.responsible_sec') }}</label>
                <input class="form-control {{ $errors->has('responsible_sec') ? 'is-invalid' : '' }}" type="text" name="responsible_sec" id="responsible_sec" value="{{ old('responsible_sec', $network->responsible_sec) }}">
                @if($errors->has('responsible_sec'))
                    <div class="invalid-feedback">
                        {{ $errors->first('responsible_sec') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.network.fields.responsible_sec_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="security_need">{{ trans('cruds.network.fields.security_need') }}</label>
                <select class="form-control select2 {{ $errors->has('security_need') ? 'is-invalid' : '' }}" name="security_need" id="security_need">
                    <option value="0" {{ ($network->security_need ? $network->security_need : old('security_need')) == 0 ? 'selected' : '' }}></option>
                    <option value="1" {{ ($network->security_need ? $network->security_need : old('security_need')) == 1 ? 'selected' : '' }}>Public</option>
                    <option value="2" {{ ($network->security_need ? $network->security_need : old('security_need')) == 2 ? 'selected' : '' }}>Internal</option>
                    <option value="3" {{ ($network->security_need ? $network->security_need : old('security_need')) == 3 ? 'selected' : '' }}>Confidential</option>
                    <option value="4" {{ ($network->security_need ? $network->security_need : old('security_need')) == 4 ? 'selected' : '' }}>Secret</option>
                </select>
                @if($errors->has('security_need'))
                    <div class="invalid-feedback">
                        {{ $errors->first('security_need') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.network.fields.security_need_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="subnetworks">{{ trans('cruds.network.fields.subnetworks') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('subnetworks') ? 'is-invalid' : '' }}" name="subnetworks[]" id="subnetworks" multiple>
                    @foreach($subnetworks as $id => $subnetworks)
                        <option value="{{ $id }}" {{ (in_array($id, old('subnetworks', [])) || $network->subnetworks->contains($id)) ? 'selected' : '' }}>{{ $subnetworks }}</option>
                    @endforeach
                </select>
                @if($errors->has('subnetworks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subnetworks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.network.fields.subnetworks_helper') }}</span>
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
  function SimpleUploadAdapter(editor) {
    editor.plugins.get('FileRepository').createUploadAdapter = function(loader) {
      return {
        upload: function() {
          return loader.file
            .then(function (file) {
              return new Promise(function(resolve, reject) {
                // Init request
                var xhr = new XMLHttpRequest();
                xhr.open('POST', '/admin/networks/ckmedia', true);
                xhr.setRequestHeader('x-csrf-token', window._token);
                xhr.setRequestHeader('Accept', 'application/json');
                xhr.responseType = 'json';

                // Init listeners
                var genericErrorText = `Couldn't upload file: ${ file.name }.`;
                xhr.addEventListener('error', function() { reject(genericErrorText) });
                xhr.addEventListener('abort', function() { reject() });
                xhr.addEventListener('load', function() {
                  var response = xhr.response;

                  if (!response || xhr.status !== 201) {
                    return reject(response && response.message ? `${genericErrorText}\n${xhr.status} ${response.message}` : `${genericErrorText}\n ${xhr.status} ${xhr.statusText}`);
                  }

                  $('form').append('<input type="hidden" name="ck-media[]" value="' + response.id + '">');

                  resolve({ default: response.url });
                });

                if (xhr.upload) {
                  xhr.upload.addEventListener('progress', function(e) {
                    if (e.lengthComputable) {
                      loader.uploadTotal = e.total;
                      loader.uploaded = e.loaded;
                    }
                  });
                }

                // Send request
                var data = new FormData();
                data.append('upload', file);
                data.append('crud_id', {{ $network->id ?? 0 }});
                xhr.send(data);
              });
            })
        }
      };
    }
  }

  var allEditors = document.querySelectorAll('.ckeditor');
  for (var i = 0; i < allEditors.length; ++i) {
    ClassicEditor.create(
      allEditors[i], {
        extraPlugins: [SimpleUploadAdapter]
      }
    );
  }
});
</script>

@endsection