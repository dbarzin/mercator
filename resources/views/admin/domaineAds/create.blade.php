@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.domaineAd.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.domaine-ads.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.domaineAd.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.domaineAd.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.domaineAd.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.domaineAd.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="domain_ctrl_cnt">{{ trans('cruds.domaineAd.fields.domain_ctrl_cnt') }}</label>
                <input class="form-control {{ $errors->has('domain_ctrl_cnt') ? 'is-invalid' : '' }}" type="number" name="domain_ctrl_cnt" id="domain_ctrl_cnt" value="{{ old('domain_ctrl_cnt', '') }}" step="1">
                @if($errors->has('domain_ctrl_cnt'))
                    <div class="invalid-feedback">
                        {{ $errors->first('domain_ctrl_cnt') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.domaineAd.fields.domain_ctrl_cnt_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="user_count">{{ trans('cruds.domaineAd.fields.user_count') }}</label>
                <input class="form-control {{ $errors->has('user_count') ? 'is-invalid' : '' }}" type="number" name="user_count" id="user_count" value="{{ old('user_count', '') }}" step="1">
                @if($errors->has('user_count'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user_count') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.domaineAd.fields.user_count_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="machine_count">{{ trans('cruds.domaineAd.fields.machine_count') }}</label>
                <input class="form-control {{ $errors->has('machine_count') ? 'is-invalid' : '' }}" type="number" name="machine_count" id="machine_count" value="{{ old('machine_count', '') }}" step="1">
                @if($errors->has('machine_count'))
                    <div class="invalid-feedback">
                        {{ $errors->first('machine_count') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.domaineAd.fields.machine_count_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="relation_inter_domaine">{{ trans('cruds.domaineAd.fields.relation_inter_domaine') }}</label>
                <input class="form-control {{ $errors->has('relation_inter_domaine') ? 'is-invalid' : '' }}" type="text" name="relation_inter_domaine" id="relation_inter_domaine" value="{{ old('relation_inter_domaine', '') }}">
                @if($errors->has('relation_inter_domaine'))
                    <div class="invalid-feedback">
                        {{ $errors->first('relation_inter_domaine') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.domaineAd.fields.relation_inter_domaine_helper') }}</span>
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
                xhr.open('POST', '/admin/domaine-ads/ckmedia', true);
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
                data.append('crud_id', {{ $domaineAd->id ?? 0 }});
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