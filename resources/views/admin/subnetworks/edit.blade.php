@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.subnetwork.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.subnetworks.update", [$subnetwork->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.subnetwork.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $subnetwork->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subnetwork.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.subnetwork.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $subnetwork->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subnetwork.fields.description_helper') }}</span>
            </div>


          <div class="row">
            <div class="col-sm">
                
                <div class="form-group">
                    <label for="address">{{ trans('cruds.subnetwork.fields.address') }}</label>
                    <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', $subnetwork->address) }}">
                    @if($errors->has('address'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.address_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="ip_range">{{ trans('cruds.subnetwork.fields.ip_range') }}</label>
                    <input class="form-control {{ $errors->has('ip_range') ? 'is-invalid' : '' }}" type="text" name="ip_range" id="ip_range" value="{{ old('ip_range', $subnetwork->ip_range) }}">
                    @if($errors->has('ip_range'))
                        <div class="invalid-feedback">
                            {{ $errors->first('ip_range') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.ip_range_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="ip_allocation_type">{{ trans('cruds.subnetwork.fields.ip_allocation_type') }}</label>
                    <input class="form-control {{ $errors->has('ip_allocation_type') ? 'is-invalid' : '' }}" type="text" name="ip_allocation_type" id="ip_allocation_type" value="{{ old('ip_allocation_type', $subnetwork->ip_allocation_type) }}">
                    @if($errors->has('ip_allocation_type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('ip_allocation_type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.ip_allocation_type_helper') }}</span>
                </div>

            </div>
            <div class="col-sm">

                <div class="form-group">
                    <label for="dmz">{{ trans('cruds.subnetwork.fields.dmz') }}</label>
                    <input class="form-control {{ $errors->has('dmz') ? 'is-invalid' : '' }}" type="text" name="dmz" id="dmz" value="{{ old('dmz', $subnetwork->dmz) }}">
                    @if($errors->has('dmz'))
                        <div class="invalid-feedback">
                            {{ $errors->first('dmz') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.dmz_helper') }}</span>
                </div>


                <div class="form-group">
                    <label for="wifi">{{ trans('cruds.subnetwork.fields.wifi') }}</label>
                    <input class="form-control {{ $errors->has('wifi') ? 'is-invalid' : '' }}" type="text" name="wifi" id="wifi" value="{{ old('wifi', $subnetwork->wifi) }}">
                    @if($errors->has('wifi'))
                        <div class="invalid-feedback">
                            {{ $errors->first('wifi') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.wifi_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="gateway_id">{{ trans('cruds.subnetwork.fields.gateway') }}</label>
                    <select class="form-control select2 {{ $errors->has('gateway') ? 'is-invalid' : '' }}" name="gateway_id" id="gateway_id">
                        @foreach($gateways as $id => $gateway)
                            <option value="{{ $id }}" {{ ($subnetwork->gateway ? $subnetwork->gateway->id : old('gateway_id')) == $id ? 'selected' : '' }}>{{ $gateway }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('gateway'))
                        <div class="invalid-feedback">
                            {{ $errors->first('gateway') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.subnetwork.fields.gateway_helper') }}</span>
                </div>
                </div>
            </div>

            <div class="form-group">
                <label for="responsible_exp">{{ trans('cruds.subnetwork.fields.responsible_exp') }}</label>
                <input class="form-control {{ $errors->has('responsible_exp') ? 'is-invalid' : '' }}" type="text" name="responsible_exp" id="responsible_exp" value="{{ old('responsible_exp', $subnetwork->responsible_exp) }}">
                @if($errors->has('responsible_exp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('responsible_exp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subnetwork.fields.responsible_exp_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="connected_subnets_id">{{ trans('cruds.subnetwork.fields.connected_subnets') }}</label>
                <select class="form-control select2 {{ $errors->has('connected_subnets') ? 'is-invalid' : '' }}" name="connected_subnets_id" id="connected_subnets_id">
                    @foreach($connected_subnets as $id => $connected_subnets)
                        <option value="{{ $id }}" {{ ($subnetwork->connected_subnets ? $subnetwork->connected_subnets->id : old('connected_subnets_id')) == $id ? 'selected' : '' }}>{{ $connected_subnets }}</option>
                    @endforeach
                </select>
                @if($errors->has('connected_subnets'))
                    <div class="invalid-feedback">
                        {{ $errors->first('connected_subnets') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.subnetwork.fields.connected_subnets_helper') }}</span>
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
                xhr.open('POST', '/admin/subnetwords/ckmedia', true);
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
                data.append('crud_id', {{ $subnetwork->id ?? 0 }});
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