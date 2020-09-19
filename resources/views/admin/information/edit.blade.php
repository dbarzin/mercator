@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.information.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.information.update", [$information->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.information.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $information->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="descrition">{{ trans('cruds.information.fields.descrition') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('descrition') ? 'is-invalid' : '' }}" name="descrition" id="descrition">{!! old('descrition', $information->descrition) !!}</textarea>
                @if($errors->has('descrition'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descrition') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.descrition_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="owner">{{ trans('cruds.information.fields.owner') }}</label>
                <input class="form-control {{ $errors->has('owner') ? 'is-invalid' : '' }}" type="text" name="owner" id="owner" value="{{ old('owner', $information->owner) }}">
                @if($errors->has('owner'))
                    <div class="invalid-feedback">
                        {{ $errors->first('owner') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.owner_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="administrator">{{ trans('cruds.information.fields.administrator') }}</label>
                <input class="form-control {{ $errors->has('administrator') ? 'is-invalid' : '' }}" type="text" name="administrator" id="administrator" value="{{ old('administrator', $information->administrator) }}">
                @if($errors->has('administrator'))
                    <div class="invalid-feedback">
                        {{ $errors->first('administrator') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.administrator_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="storage">{{ trans('cruds.information.fields.storage') }}</label>
                <input class="form-control {{ $errors->has('storage') ? 'is-invalid' : '' }}" type="text" name="storage" id="storage" value="{{ old('storage', $information->storage) }}">
                @if($errors->has('storage'))
                    <div class="invalid-feedback">
                        {{ $errors->first('storage') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.storage_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="processes">{{ trans('cruds.information.fields.process') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
                    @foreach($processes as $id => $process)
                        <option value="{{ $id }}" {{ (in_array($id, old('processes', [])) || $information->processes->contains($id)) ? 'selected' : '' }}>{{ $process }}</option>
                    @endforeach
                </select>
                @if($errors->has('processes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('processes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.process_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="security_need">{{ trans('cruds.information.fields.security_need') }}</label>
                <select class="form-control select2 {{ $errors->has('security_need') ? 'is-invalid' : '' }}" name="security_need" id="security_need">
                    <option value="0" {{ ($information->security_need ? $information->security_need : old('security_need')) == 0 ? 'selected' : '' }}></option>
                    <option value="1" {{ ($information->security_need ? $information->security_need : old('security_need')) == 1 ? 'selected' : '' }}>Public</option>
                    <option value="2" {{ ($information->security_need ? $information->security_need : old('security_need')) == 2 ? 'selected' : '' }}>Internal</option>
                    <option value="3" {{ ($information->security_need ? $information->security_need : old('security_need')) == 3 ? 'selected' : '' }}>Confidential</option>
                    <option value="4" {{ ($information->security_need ? $information->security_need : old('security_need')) == 4 ? 'selected' : '' }}>Secret</option>
                </select>
                <span class="help-block">{{ trans('cruds.information.fields.security_need_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="sensitivity">{{ trans('cruds.information.fields.sensitivity') }}</label>
                <input class="form-control {{ $errors->has('sensitivity') ? 'is-invalid' : '' }}" type="text" name="sensitivity" id="sensitivity" value="{{ old('sensitivity', $information->sensitivity) }}">
                @if($errors->has('sensitivity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sensitivity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.sensitivity_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="constraints">{{ trans('cruds.information.fields.constraints') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('constraints') ? 'is-invalid' : '' }}" name="constraints" id="constraints">{!! old('constraints', $information->constraints) !!}</textarea>
                @if($errors->has('constraints'))
                    <div class="invalid-feedback">
                        {{ $errors->first('constraints') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.constraints_helper') }}</span>
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
                xhr.open('POST', '/admin/information/ckmedia', true);
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
                data.append('crud_id', {{ $information->id ?? 0 }});
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