@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.operation.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.operations.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.operation.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.operation.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.operation.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.operation.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="actors">{{ trans('cruds.operation.fields.actors') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('actors') ? 'is-invalid' : '' }}" name="actors[]" id="actors" multiple>
                    @foreach($actors as $id => $actors)
                        <option value="{{ $id }}" {{ in_array($id, old('actors', [])) ? 'selected' : '' }}>{{ $actors }}</option>
                    @endforeach
                </select>
                @if($errors->has('actors'))
                    <div class="invalid-feedback">
                        {{ $errors->first('actors') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.operation.fields.actors_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="tasks">{{ trans('cruds.operation.fields.tasks') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('tasks') ? 'is-invalid' : '' }}" name="tasks[]" id="tasks" multiple>
                    @foreach($tasks as $id => $tasks)
                        <option value="{{ $id }}" {{ in_array($id, old('tasks', [])) ? 'selected' : '' }}>{{ $tasks }}</option>
                    @endforeach
                </select>
                @if($errors->has('tasks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('tasks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.operation.fields.tasks_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="activities">{{ trans('cruds.operation.fields.activities') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('activities') ? 'is-invalid' : '' }}" name="activities[]" id="activities" multiple>
                    @foreach($activities as $id => $name)
                        <option value="{{ $id }}" {{ in_array($id, old('activities', [])) ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @if($errors->has('activities'))
                    <div class="invalid-feedback">
                        {{ $errors->first('activities') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.operation.fields.activities_helper') }}</span>
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
                xhr.open('POST', '/admin/operations/ckmedia', true);
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
                data.append('crud_id', {{ $operation->id ?? 0 }});
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