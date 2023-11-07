@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.physical-servers.update", [$physicalServer->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
<div class="card">
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.physicalServer.title_singular') }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.physicalServer.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $physicalServer->name) }}" required>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.name_helper') }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="type">{{ trans('cruds.physicalServer.fields.type') }}</label>
                    <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                        @if (!$type_list->contains(old('type')))
                            <option> {{ old('type') }}</option>'
                        @endif
                        @foreach($type_list as $t)
                            <option {{ (old('type') ? old('type') : $physicalServer->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.type_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg">
                <div class="form-group">
                    <label for="description">{{ trans('cruds.physicalServer.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $physicalServer->description) !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.description_helper') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans('cruds.physicalServer.fields.configuration') }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="cpu">{{ trans('cruds.physicalServer.fields.cpu') }}</label>
                    <input class="form-control {{ $errors->has('cpu') ? 'is-invalid' : '' }}" type="text" name="cpu" id="cpu" value="{{ old('cpu', $physicalServer->cpu) }}">
                    @if($errors->has('cpu'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cpu') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.cpu_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="memory">{{ trans('cruds.physicalServer.fields.memory') }}</label>
                    <input class="form-control {{ $errors->has('memory') ? 'is-invalid' : '' }}" type="text" name="memory" id="memory" value="{{ old('memory', $physicalServer->memory) }}">
                    @if($errors->has('memory'))
                        <div class="invalid-feedback">
                            {{ $errors->first('memory') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.memory_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="disk">{{ trans('cruds.physicalServer.fields.disk') }}</label>
                    <input class="form-control {{ $errors->has('disk') ? 'is-invalid' : '' }}" type="text" name="disk" id="disk" value="{{ old('disk', $physicalServer->disk) }}">
                    @if($errors->has('disk'))
                        <div class="invalid-feedback">
                            {{ $errors->first('disk') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.disk_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="disk_used">{{ trans('cruds.physicalServer.fields.disk_used') }}</label>
                    <input class="form-control {{ $errors->has('disk_used') ? 'is-invalid' : '' }}" type="text" name="disk_used" id="disk_used" value="{{ old('disk_used', $physicalServer->disk_used) }}">
                    @if($errors->has('disk_used'))
                        <div class="invalid-feedback">
                            {{ $errors->first('disk_used') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.disk_used_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg">
                <div class="form-group">
                    <textarea class="form-control ckeditor {{ $errors->has('configuration') ? 'is-invalid' : '' }}" name="configuration" id="configuration">{!! old('configuration', $physicalServer->configuration) !!}</textarea>
                    @if($errors->has('configuration'))
                        <div class="invalid-feedback">
                            {{ $errors->first('configuration') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.configuration_helper') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.application.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-lg">
                <div class="form-group">
                    <label for="applications">{{ trans('cruds.physicalServer.fields.applications') }}</label>
                    <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                        @foreach($application_list as $id => $name)
                            <option value="{{ $id }}" {{ (in_array($id, old('applications', [])) || $physicalServer->applications->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('applications'))
                        <div class="invalid-feedback">
                            {{ $errors->first('applications') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.applications_helper') }}</span>
                </div>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.logical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label class="recommended" for="operating_system">{{ trans('cruds.physicalServer.fields.operating_system') }}</label>
                    <select class="form-control select2-free {{ $errors->has('operating_system') ? 'is-invalid' : '' }}" name="operating_system" id="operating_system">
                        @if (!$operating_system_list->contains(old('operating_system')))
                            <option> {{ old('operating_system') }}</option>'
                        @endif
                        @foreach($operating_system_list as $t)
                            <option {{ (old('operating_system') ? old('operating_system') : $physicalServer->operating_system) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('operating_system'))
                        <div class="invalid-feedback">
                            {{ $errors->first('operating_system') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.operating_system_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="install_date">{{ trans('cruds.physicalServer.fields.install_date') }}</label>
                    <input class="form-control datetime" type="text" name="install_date" id="install_date" value="{{ old('install_date', $physicalServer->install_date) }}">
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.install_date_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="update_date">{{ trans('cruds.physicalServer.fields.update_date') }}</label>
                    <input class="datetime form-control" type="text" id="update_date" name="update_date" value="{{ old('update_date', $physicalServer->update_date) }}">
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.update_date_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <label for="responsible">{{ trans('cruds.physicalServer.fields.responsible') }}</label>
                    <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsible" id="responsible">
                        @if (!$responsible_list->contains(old('responsible')))
                            <option> {{ old('responsible') }}</option>'
                        @endif
                        @foreach($responsible_list as $t)
                            <option {{ (old('responsible') ? old('responsible') : $physicalServer->responsible) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.responsible_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label class="recommended" for="address_ip">{{ trans('cruds.physicalServer.fields.address_ip') }}</label>
                    <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text" name="address_ip" id="address_ip" value="{{ old('address_ip', $physicalServer->address_ip) }}">
                    @if($errors->has('address_ip'))
                        <div class="invalid-feedback">
                            {{ $errors->first('address_ip') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.address_ip_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="form-group">
                    <label for="cluster_id">{{ trans('cruds.physicalServer.fields.cluster') }}</label>
                    <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="cluster_id" id="cluster_id">
                        <option></option>
                        @foreach($clusters as $id => $name)
                            <option value="{{ $id }}" {{ ($physicalServer->cluster ? $physicalServer->cluster>id : old('cluster_id')) == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('cluster'))
                        <div class="invalid-feedback">
                            {{ $errors->first('cluster') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.cluster_helper') }}</span>
                </div>
            </div>
            <div class="col-lg-8">
                <div class="form-group">
                    <label for="applications">{{ trans('cruds.physicalServer.fields.logical_servers') }}</label>
                    <select class="form-control select2 {{ $errors->has('logicalServers') ? 'is-invalid' : '' }}" name="logicalServers[]" id="logicalServers" multiple>
                        @foreach($logicalServer_list as $id => $name)
                            <option value="{{ $id }}" {{ (in_array($id, old('logicalServers', [])) || $physicalServer->serversLogicalServers->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('logicalServers'))
                        <div class="invalid-feedback">
                            {{ $errors->first('logicalServers') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalServer.fields.logical_servers_helper') }}</span>
                </div>
            </div>
        </div>

    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.physical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
      <div class="row">
          <div class="col-md-4">
            <div class="form-group">
                <label for="site_id">{{ trans('cruds.physicalServer.fields.site') }}</label>
                <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                    <option></option>
                    @foreach($sites as $id => $site)
                        <option value="{{ $id }}" {{ ($physicalServer->site ? $physicalServer->site->id : old('site_id')) == $id ? 'selected' : '' }}>{{ $site }}</option>
                    @endforeach
                </select>
                @if($errors->has('site'))
                    <div class="invalid-feedback">
                        {{ $errors->first('site') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.site_helper') }}</span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                <label for="building_id">{{ trans('cruds.physicalServer.fields.building') }}</label>
                <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}" name="building_id" id="building_id">
                    <option></option>
                    @foreach($buildings as $id => $building)
                        <option value="{{ $id }}" {{ ($physicalServer->building ? $physicalServer->building->id : old('building_id')) == $id ? 'selected' : '' }}>{{ $building }}</option>
                    @endforeach
                </select>
                @if($errors->has('building'))
                    <div class="invalid-feedback">
                        {{ $errors->first('building') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.building_helper') }}</span>
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
                <label for="bay_id">{{ trans('cruds.physicalServer.fields.bay') }}</label>
                <select class="form-control select2 {{ $errors->has('bay') ? 'is-invalid' : '' }}" name="bay_id" id="bay_id">
                    <option></option>
                    @foreach($bays as $id => $bay)
                        <option value="{{ $id }}" {{ ($physicalServer->bay ? $physicalServer->bay->id : old('bay_id')) == $id ? 'selected' : '' }}>{{ $bay }}</option>
                    @endforeach
                </select>
                @if($errors->has('bay'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bay') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalServer.fields.bay_helper') }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="form-group">
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

  $(".select2-free").select2({
        placeholder: "{{ trans('global.pleaseSelect') }}",
        allowClear: true,
        tags: true
    })

});
</script>
@endsection
