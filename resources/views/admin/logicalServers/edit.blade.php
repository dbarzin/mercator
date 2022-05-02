@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.logicalServer.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.logical-servers.update", [$logicalServer->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.logicalServer.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $logicalServer->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.logicalServer.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.logicalServer.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $logicalServer->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.logicalServer.fields.description_helper') }}</span>
            </div>

          <div class="row">
            <div class="col-sm">
                
                <div class="form-group">
                    <label class="recommended" for="operating_system">{{ trans('cruds.logicalServer.fields.operating_system') }}</label>
                    <select class="form-control select2-free {{ $errors->has('operating_system') ? 'is-invalid' : '' }}" name="operating_system" id="operating_system">
                        @if (!$operating_system_list->contains(old('operating_system')))
                            <option> {{ old('operating_system') }}</option>'
                        @endif
                        @foreach($operating_system_list as $t)
                            <option {{ (old('operating_system') ? old('operating_system') : $logicalServer->operating_system) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('operating_system'))
                        <div class="invalid-feedback">
                            {{ $errors->first('operating_system') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.operating_system_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="install_date">{{ trans('cruds.logicalServer.fields.install_date') }}</label>
                    <input class="form-control datetime" type="text" name="install_date" id="install_date" value="{{ old('install_date', $logicalServer->install_date) }}">
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.install_date_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="update_date">{{ trans('cruds.logicalServer.fields.update_date') }}</label>
                    <input class="datetime form-control" type="text" id="update_date" name="update_date" value="{{ old('update_date', $logicalServer->update_date) }}">
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.update_date_helper') }}</span>
                </div>

            </div>
            <div class="col-sm">

                <div class="form-group">
                    <label for="cpu">{{ trans('cruds.logicalServer.fields.cpu') }}</label>
                    <input class="form-control {{ $errors->has('cpu') ? 'is-invalid' : '' }}" type="text" name="cpu" id="cpu" value="{{ old('cpu', $logicalServer->cpu) }}">
                    @if($errors->has('cpu'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cpu') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.cpu_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="memory">{{ trans('cruds.logicalServer.fields.memory') }}</label>
                    <input class="form-control {{ $errors->has('memory') ? 'is-invalid' : '' }}" type="text" name="memory" id="memory" value="{{ old('memory', $logicalServer->memory) }}">
                    @if($errors->has('memory'))
                        <div class="invalid-feedback">
                            {{ $errors->first('memory') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.memory_helper') }}</span>
                </div>

                <div class="form-group">
                    <label for="disk">{{ trans('cruds.logicalServer.fields.disk') }}</label>
                    <input class="form-control {{ $errors->has('disk') ? 'is-invalid' : '' }}" type="text" name="disk" id="disk" value="{{ old('disk', $logicalServer->disk) }}">
                    @if($errors->has('memory'))
                        <div class="invalid-feedback">
                            {{ $errors->first('disk') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.disk_helper') }}</span>
                </div>

            </div>
            <div class="col-sm">

                <div class="form-group">
                    <label class="recommended" for="environment">{{ trans('cruds.logicalServer.fields.environment') }}</label>
                    <select class="form-control select2-free {{ $errors->has('environment') ? 'is-invalid' : '' }}" name="environment" id="environment">
                        @if (!$environment_list->contains(old('environment')))
                            <option> {{ old('environment') }}</option>'
                        @endif
                        @foreach($environment_list as $t)
                            <option {{ (old('environment') ? old('environment') : $logicalServer->environment) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('environment'))
                        <div class="invalid-feedback">
                            {{ $errors->first('environment') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.environment_helper') }}</span>
                </div>

                <div class="form-group">
                    <label class="recommended" for="address_ip">{{ trans('cruds.logicalServer.fields.address_ip') }}</label>
                    <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text" name="address_ip" id="address_ip" value="{{ old('address_ip', $logicalServer->address_ip) }}">
                    @if($errors->has('address_ip'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address_ip') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.address_ip_helper') }}</span>
                </div>

                <div class="form-group">                
                    <label for="net_services">{{ trans('cruds.logicalServer.fields.net_services') }}</label>
                    <input class="form-control {{ $errors->has('net_services') ? 'is-invalid' : '' }}" type="text" name="net_services" id="net_services" value="{{ old('net_services', $logicalServer->net_services) }}">
                    @if($errors->has('net_services'))
                        <div class="invalid-feedback">
                            {{ $errors->first('net_services') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.net_services_helper') }}</span>
                </div>

            </div>
        </div>

            <div class="form-group">
                <label for="configuration">{{ trans('cruds.logicalServer.fields.configuration') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('configuration') ? 'is-invalid' : '' }}" name="configuration" id="configuration">{!! old('configuration', $logicalServer->configuration) !!}</textarea>
                @if($errors->has('configuration'))
                    <div class="invalid-feedback">
                        {{ $errors->first('configuration') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.logicalServer.fields.configuration_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="applications">{{ trans('cruds.logicalServer.fields.applications') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                    @foreach($applications as $id => $applications)
                        <option value="{{ $id }}" {{ (in_array($id, old('applications', [])) || $logicalServer->applications->contains($id)) ? 'selected' : '' }}>{{ $applications }}</option>
                    @endforeach
                </select>
                @if($errors->has('servers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('servers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.logicalServer.fields.applications_helper') }}</span>
            </div>


            <div class="form-group">
                <label class="recommended" for="servers">{{ trans('cruds.logicalServer.fields.servers') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('servers') ? 'is-invalid' : '' }}" name="servers[]" id="servers" multiple>
                    @foreach($servers as $id => $servers)
                        <option value="{{ $id }}" {{ (in_array($id, old('servers', [])) || $logicalServer->servers->contains($id)) ? 'selected' : '' }}>{{ $servers }}</option>
                    @endforeach
                </select>
                @if($errors->has('servers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('servers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.logicalServer.fields.servers_helper') }}</span>
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