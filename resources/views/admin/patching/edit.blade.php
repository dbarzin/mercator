@extends('layouts.admin')
@section('content')

<form method="POST" action='{{ route("admin.patching.update") }}' enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <input type='hidden' name="id" value='{{$server->id}}'/>
    <div class="card">
    <!---------------------------------------------------------------------------------------------------->
        <div class="card-header">
        {{ trans('panel.menu.patching') }}
        </div>
    <!---------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <dt>{{ trans('cruds.logicalServer.fields.name') }}</dt>
                    {{ $server->name }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <dt>{{ trans('cruds.logicalServer.fields.description') }}</dt>
                    {!! $server->description !!}
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
                            <option {{ (old('operating_system') ? old('operating_system') : $server->operating_system) == $t ? 'selected' : '' }}>{{$t}}</option>
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
                    <input class="form-control datetime" type="text" name="install_date" id="install_date" value="{{ old('install_date', $server->install_date) }}">
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.install_date_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="recommended" for="environment">{{ trans('cruds.logicalServer.fields.environment') }}</label>
                    <select class="form-control select2-free {{ $errors->has('environment') ? 'is-invalid' : '' }}" name="environment" id="environment">
                        @if (!$environment_list->contains(old('environment')))
                            <option> {{ old('environment') }}</option>'
                        @endif
                        @foreach($environment_list as $t)
                            <option {{ (old('environment') ? old('environment') : $server->environment) == $t ? 'selected' : '' }}>{{$t}}</option>
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
            <div class="col-md-8">
                <div class="form-group">
                    <label class="recommended" for="address_ip">{{ trans('cruds.logicalServer.fields.address_ip') }}</label>
                    <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text" name="address_ip" id="address_ip" value="{{ old('address_ip', $server->address_ip) }}">
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
                    <input class="form-control {{ $errors->has('net_services') ? 'is-invalid' : '' }}" type="text" name="net_services" id="net_services" value="{{ old('net_services', $server->net_services) }}">
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
                    <input class="form-control {{ $errors->has('cpu') ? 'is-invalid' : '' }}" type="text" name="cpu" id="cpu" value="{{ old('cpu', $server->cpu) }}">
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
                    <input class="form-control {{ $errors->has('memory') ? 'is-invalid' : '' }}" type="text" name="memory" id="memory" value="{{ old('memory', $server->memory) }}">
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
                    <input class="form-control {{ $errors->has('disk') ? 'is-invalid' : '' }}" type="text" name="disk" id="disk" value="{{ old('disk', $server->disk) }}">
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
                    <input class="form-control {{ $errors->has('disk_used') ? 'is-invalid' : '' }}" type="text" name="disk_used" id="disk_used" value="{{ old('disk_used', $server->disk_used) }}">
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
                    <textarea class="form-control ckeditor {{ $errors->has('configuration') ? 'is-invalid' : '' }}" name="configuration" id="configuration">{!! old('configuration', $server->configuration) !!}</textarea>
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
        Patching
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">

        <div class="row">
            <div class="col-sm">      
                <div class="form-group">
                    <label class="recommended" for="patching_group">{{ trans('cruds.logicalServer.fields.patching_group') }}</label>
                    <select class="form-control select2-free {{ $errors->has('patching_group') ? 'is-invalid' : '' }}" name="patching_group" id="patching_group">
                        @if (!$environment_list->contains(old('patching_group')))
                            <option> {{ old('patching_group') }}</option>'
                        @endif
                        @foreach($patching_group_list as $t)
                            <option {{ (old('patching_group') ? old('patching_group') : $server->patching_group) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('patching_group'))
                        <div class="invalid-feedback">
                            {{ $errors->first('patching_group') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.patching_group_helper') }}</span>
                </div>
            </div>

            <div class="col-sm">      
                <div class="form-group">
                    <label for="update_date">{{ trans('cruds.logicalServer.fields.update_date') }}</label>
                    <table width="100%">
                        <tr>
                            <td width="90%">
                            <input class="datetime form-control" type="text" id="update_date" name="update_date" value="{{ old('update_date', $server->update_date) }}">
                            </td>
                            <td>
                                <a href='' class="nav-link" onclick="document.getElementById('update_date').value='{{ now()->format('m/d/Y H:i:s') }}';return false;">
                                    <i class=" nav-icon fas fa-clock">
                                    </i>
                                </a>
                            </td>
                        </tr>
                    </table>
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.update_date_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">      
                <div class="form-group">
                    <label for="next_update">{{ trans('cruds.logicalServer.fields.next_update') }}</label>
                    <table width="100%">
                        <tr>
                            <td width="90%">
                            <input class="date form-control" type="text" id="next_update" name="next_update" value="{{ old('next_update', $server->next_update) }}">
                            </td>
                        </tr>
                    </table>
                    <span class="help-block">{{ trans('cruds.logicalServer.fields.next_update_helper') }}</span>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-sm">

                <div class="form-group">
                    <label class="recommended" for="controls">{{ trans('cruds.dataProcessing.fields.documents') }}</label>
                    <div class="dropzone dropzone-previews" id="dropzoneFileUpload"></div>
                    @if($errors->has('documents'))
                        <div class="invalid-feedback">
                            {{ $errors->first('documents') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.dataProcessing.fields.documents_helper') }}</span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm">
                <div class="form-group">
                    <button class="btn btn-danger" type="submit">
                        {{ trans('global.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script src="/js/dropzone.js"></script>

<script>
Dropzone.autoDiscover = false;


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


    var image_uploader = new Dropzone("#dropzoneFileUpload", { 
        url: '/admin/documents/store',
        headers: { 'x-csrf-token': '{{csrf_token()}}' },
        params: { },
            maxFilesize: 10,
            // acceptedFiles: ".jpeg,.jpg,.png,.gif",
            addRemoveLinks: true,
            timeout: 50000,
            removedfile: function(file) 
            {
                console.log("remove file " + file.name + " " + file.id);
                $.ajax({
                    headers: {
                      'X-CSRF-TOKEN': '{{csrf_token()}}'
                       },
                    type: 'GET',
                    url: '{{ url( "/admin/documents/delete" ) }}'+"/"+file.id,
                    success: function (data){
                        console.log("File has been successfully removed");
                    },
                    error: function(e) {
                        console.log("File not removed");
                        console.log(e);
                    }});
                    // console.log('{{ url( "/documents/delete" ) }}'+"/"+file.id+']');
                    var fileRef;
                    return (fileRef = file.previewElement) != null ? 
                    fileRef.parentNode.removeChild(file.previewElement) : void 0;
            },
            success: function(file, response) 
            {
                file.id=response.id;
                console.log("success response");
                console.log(response);
            },
            error: function(file, response)
            {
                console.log("error response");
                console.log(response);
               return false;
            },
            init: function () {
            //Add existing files into dropzone            
            var existingFiles = [
                @foreach($server->documents as $document)
                    { name: "{{ $document->filename }}", size: {{ $document->size }}, id: {{ $document->id }} },                    
                @endforeach
            ];
            for (i = 0; i < existingFiles.length; i++) {
                this.emit("addedfile", existingFiles[i]);                
                this.emit("complete", existingFiles[i]);                
                }
            }
        });
    });
</script>



});
</script>
@endsection