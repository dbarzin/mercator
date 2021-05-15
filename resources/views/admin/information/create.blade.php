@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.information.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.information.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.information.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="descrition">{{ trans('cruds.information.fields.descrition') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('descrition') ? 'is-invalid' : '' }}" name="descrition" id="descrition">{!! old('descrition') !!}</textarea>
                @if($errors->has('descrition'))
                    <div class="invalid-feedback">
                        {{ $errors->first('descrition') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.descrition_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-sm">

                    <div class="form-group">
                        <label for="processes">{{ trans('cruds.information.fields.process') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
                            @foreach($processes as $id => $process)
                                <option value="{{ $id }}" {{ in_array($id, old('processes', [])) ? 'selected' : '' }}>{{ $process }}</option>
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
                        <label class="recommended" for="storage">{{ trans('cruds.information.fields.storage') }}</label>
                        <input class="form-control {{ $errors->has('storage') ? 'is-invalid' : '' }}" type="text" name="storage" id="storage" value="{{ old('storage', '') }}">
                        @if($errors->has('storage'))
                            <div class="invalid-feedback">
                                {{ $errors->first('storage') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.storage_helper') }}</span>
                    </div>

                </div>

                <div class="col-sm">

                    <div class="form-group">
                        <label class="recommended" for="owner">{{ trans('cruds.information.fields.owner') }}</label>
                        <input class="form-control {{ $errors->has('owner') ? 'is-invalid' : '' }}" type="text" name="owner" id="owner" value="{{ old('owner', '') }}">
                        @if($errors->has('owner'))
                            <div class="invalid-feedback">
                                {{ $errors->first('owner') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.owner_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="recommended" for="administrator">{{ trans('cruds.information.fields.administrator') }}</label>
                        <input class="form-control {{ $errors->has('administrator') ? 'is-invalid' : '' }}" type="text" name="administrator" id="administrator" value="{{ old('administrator', '') }}">
                        @if($errors->has('administrator'))
                            <div class="invalid-feedback">
                                {{ $errors->first('administrator') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.administrator_helper') }}</span>
                    </div>

                </div>
            </div>
        
            <div class="row">
                <div class="col-sm">

                    <div class="form-group">
                        <table cellspacing="5" cellpadding="5" border="0" width='100%'>
                            <tr>
                                <td width='20%'>
                                    <label 
                                        @if (auth()->user()->granularity>=2)                                    
                                            class="recommended" 
                                        @endif
                                        for="security_need">{{ trans('cruds.information.fields.security_need') }}</label>
                                </td>
                                <td align="right" width="10">
                                    <label for="security_need">C</label>
                                </td>
                                <td width="120">
                                    <select class="form-control select2 {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}" name="security_need_c" id="security_need_c">
                                        <option value="0" {{ old('security_need_c') == 0 ? 'selected' : '' }}></option>
                                        <option value="1" {{ old('security_need_c') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ old('security_need_c') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ old('security_need_c') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ old('security_need_c') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">I</label>
                                </td>
                                <td  width="120">
                                    <select class="form-control select2 {{ $errors->has('security_need_i') ? 'is-invalid' : '' }}" name="security_need_i" id="security_need_i">
                                        <option value="0" {{ old('security_need_i') == 0 ? 'selected' : '' }}></option>
                                        <option value="1" {{ old('security_need_i') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ old('security_need_i') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ old('security_need_i') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ old('security_need_i') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">D</label>
                                </td>
                                <td width="120">
                                    <select class="form-control select2 {{ $errors->has('security_need_a') ? 'is-invalid' : '' }}" name="security_need_a" id="security_need_a">
                                        <option value="0" {{ old('security_need_a') == 0 ? 'selected' : '' }}></option>
                                        <option value="1" {{ old('security_need_a') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ old('security_need_a') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ old('security_need_a') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ old('security_need_a') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">T</label>
                                </td>
                                <td width="120">
                                    <select class="form-control select2 {{ $errors->has('security_need_t') ? 'is-invalid' : '' }}" name="security_need_t" id="security_need_t">
                                        <option value="0" {{ old('security_need_t') == 0 ? 'selected' : '' }}></option>
                                        <option value="1" {{ old('security_need_t') == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ old('security_need_t') == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ old('security_need_t') == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ old('security_need_t') == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        @if($errors->has('security_need'))
                            <div class="invalid-feedback">
                                {{ $errors->first('security_need') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.security_need_helper') }}</span>
                    </div>

                </div>
                <div class="col-sm">

                    <div class="form-group">
                        <label class="recommended" for="sensitivity">{{ trans('cruds.information.fields.sensitivity') }}</label>
                        <input class="form-control {{ $errors->has('sensitivity') ? 'is-invalid' : '' }}" type="text" name="sensitivity" id="sensitivity" value="{{ old('sensitivity', '') }}">
                        @if($errors->has('sensitivity'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sensitivity') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.sensitivity_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="constraints">{{ trans('cruds.information.fields.constraints') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('constraints') ? 'is-invalid' : '' }}" name="constraints" id="constraints">{!! old('constraints') !!}</textarea>
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