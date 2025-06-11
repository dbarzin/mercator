@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.logical-servers.update", [$logicalServer->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
<div class="card">
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.logicalServer.title_singular') }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.logicalServer.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $logicalServer->name) }}" required maxlength='32' autofocus/>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.logicalServer.fields.name_helper') }}</span>
            </div>
        </div>
        <div class="col-md-3">
            <div class="form-group">
                <label for="type">{{ trans('cruds.logicalServer.fields.type') }}</label>
                <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" maxlength='255'>
                    @if (!$type_list->contains(old('type')))
                        <option> {{ old('type') }}</option>'
                    @endif
                    @foreach($type_list as $t)
                        <option {{ (old('type') ? old('type') : $logicalServer->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.logicalServer.fields.type_helper') }}</span>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="form-group">
                <label for="attributes">{{ trans('cruds.logicalServer.fields.attributes') }}</label>
                <select class="form-control select2-free {{ $errors->has('patching_group') ? 'is-invalid' : '' }}" name="attributes[]" id="attributes[]" multiple>
                    @foreach($attributes_list as $a)
                        <option {{ ( (old('attributes')!=null) && in_array($a,old('attributes'))) || str_contains($logicalServer->attributes, $a) ? 'selected' : '' }}>{{$a}}</option>
                    @endforeach
                </select>
                @if($errors->has('attributes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('attributes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.logicalServer.fields.attributes_helper') }}</span>
            </div>
        </div>
        <div class="col-sm-1">
            <div class="form-check">
                <label for="crypted">{{ trans('cruds.logicalServer.fields.active') }}</label>
                <div class="form-switch">
                  <input class="form-check-input" type="checkbox" id="active" name="active" {{ $logicalServer->active ? "checked" : "" }}>
                </div>
            </div>
        </div>

      </div>
      <!----------------------------------------------------------------------->
      <div class="row">
        <div class="col-9">
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
            </div>
            <div class="col-3">
                <div class="form-group">
                    <label for="iconSelect">{{ trans('global.icon_select') }}</label>
                    <select id="iconSelect" name="iconSelect" class="form-control"></select>
                </div>
                <div class="form-group">
                    <input type="file" id="iconFile" name="iconFile" accept="image/png" />
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
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="install_date">{{ trans('cruds.logicalServer.fields.install_date') }}</label>
                    <input class="form-control" type="date" name="install_date" id="install_date" value="{{ old('install_date', $logicalServer->install_date) }}">
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.install_date_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="update_date">{{ trans('cruds.logicalServer.fields.update_date') }}</label>
                    <input class="form-control" type="date" id="update_date" name="update_date" value="{{ old('update_date', $logicalServer->update_date) }}">
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.update_date_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="recommended" for="cluster_id">{{ trans('cruds.logicalServer.fields.cluster') }}</label>
                    <select class="form-control select2 {{ $errors->has('cluster') ? 'is-invalid' : '' }}" name="cluster_id" id="cluster_id">
                        <option></option>
                        @foreach($clusters as $id => $cluster)
                        <option value="{{ $id }}" {{ ($logicalServer->cluster ? $logicalServer->cluster->id : old('cluster_id')) == $id ? 'selected' : '' }}>{{ $cluster }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('cluster'))
                    <div class="invalid-feedback">
                        {{ $errors->first('cluster') }}
                    </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.cluster_helper') }}</span>
                </div>
            </div>
            <div class="col-md-4">
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
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">
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
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
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

    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.logicalServer.fields.configuration") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
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
            </div>
            <div class="col-sm">
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
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="disk">{{ trans('cruds.logicalServer.fields.disk') }}</label>
                    <input class="form-control {{ $errors->has('disk') ? 'is-invalid' : '' }}" type="text" name="disk" id="disk" value="{{ old('disk', $logicalServer->disk) }}">
                    @if($errors->has('disk'))
                        <div class="invalid-feedback">
                            {{ $errors->first('disk') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.disk_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label for="disk_used">{{ trans('cruds.logicalServer.fields.disk_used') }}</label>
                    <input class="form-control {{ $errors->has('disk_used') ? 'is-invalid' : '' }}" type="text" name="disk_used" id="disk_used" value="{{ old('disk_used', $logicalServer->disk_used) }}">
                    @if($errors->has('disk_used'))
                        <div class="invalid-feedback">
                            {{ $errors->first('disk_used') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.disk_used_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <textarea class="form-control ckeditor {{ $errors->has('configuration') ? 'is-invalid' : '' }}" name="configuration" id="configuration">{!! old('configuration', $logicalServer->configuration) !!}</textarea>
                    @if($errors->has('configuration'))
                        <div class="invalid-feedback">
                            {{ $errors->first('configuration') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.configuration_helper') }}</span>
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
                <div class="col-sm">

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
                        @if($errors->has('applications'))
                            <div class="invalid-feedback">
                                {{ $errors->first('applications') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalServer.fields.applications_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="databases">{{ trans('cruds.logicalServer.fields.databases') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('databases') ? 'is-invalid' : '' }}" name="databases[]" id="databases" multiple>
                            @foreach($databases as $id => $name)
                                <option value="{{ $id }}" {{ (in_array($id, old('databases', [])) || $logicalServer->databases->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('databases'))
                            <div class="invalid-feedback">
                                {{ $errors->first('databases') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalServer.fields.databases_helper') }}</span>
                    </div>
                </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.administration.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label class="recommended" for="domain">{{ trans('cruds.logicalServer.fields.domain') }}</label>
                    <select class="form-control select2 {{ $errors->has('domains') ? 'is-invalid' : '' }}" name="domain_id" id="domain_id">
                        @foreach($domains as $id => $name)
                            <option value="{{ $id }}" {{ $id==old('domain_id', $logicalServer->domain_id) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('domain_id'))
                        <div class="invalid-feedback">
                            {{ $errors->first('domain_id') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.domain_helper') }}</span>
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
            <div class="col-md-6">
                    <div class="form-group">
                        <label class="recommended" for="physicalServers">{{ trans('cruds.logicalServer.fields.servers') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('physicalServers') ? 'is-invalid' : '' }}" name="physicalServers[]" id="physicalServers" multiple>
                            @foreach($physicalServers as $id => $name)
                                <option value="{{ $id }}" {{ (in_array($id, old('physicalServers', [])) || $logicalServer->physicalServers->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('physicalServers'))
                            <div class="invalid-feedback">
                                {{ $errors->first('physicalServers') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalServer.fields.servers_helper') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.logical-servers.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button id="btn-save" class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection


@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ---------------------------------------------------------------------
    // Initialize imageSelect
	imagesData =
		[
            {
                value: '-1',
                img: '/images/lserver.png',
                imgWidth: '120px',
                imgHeight: '120px',
                selected: {{ $logicalServer->icon_id === null ? "true" : "false"}},
            },
            @foreach($icons as  $icon)
            {
                value: '{{ $icon }}',
                img: '{{ route('admin.documents.show', $icon) }}',
                imgWidth: '120px',
                imgHeight: '120px',
                selected: {{ $logicalServer->icon_id === $icon ? "true" : "false" }},
            },
            @endforeach
        ];

    // Initialize the Dynamic Selects
    dynamicSelect = new DynamicSelect('#iconSelect', {
        columns: 2,
        height: '140px',
        width: '160px',
        dropdownWidth: '300px',
        placeholder: 'Select an icon',
        data: imagesData,
    });

    // Handle file upload and verification
    $('#iconFile').on('change', function(e) {
        const file = e.target.files[0];

        if (file && file.type === 'image/png') {
            const img = new Image();
            img.src = URL.createObjectURL(file);

            img.onload = function() {
                // Check size
                if (img.size > 65535) {
                    alert('Image size must be < 65kb');
                    return;
                    }
                if ((img.width > 255) || (img.height > 255)) {
                    alert('Could not be more than 256x256 pixels.');
                    return;
                }

                // Encode the image in base64
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Add the image to the select2 options
					imagesData.push(
		                {
		                    value: file.name,
		                    img: event.target.result,
		                    imgHeight: '100px',
		                });
					// refresh
                    dynamicSelect.refresh(imagesData, file.name);
                };
                reader.readAsDataURL(file);
            }
        } else {
            alert('Select a PNG image.');
        }
    });
});
</script>
@endsection
