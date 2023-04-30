@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.activity.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.activities.store") }}" enctype="multipart/form-data" >
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.activity.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.activity.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.description_helper') }}</span>
            </div>


            <div class="row">
                <div class="col-6">

                    <div class="form-group">
                        <label for="operations">{{ trans('cruds.activity.fields.processes') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
                            @foreach($processes as $id => $processes)
                                <option value="{{ $id }}" {{ in_array($id, old('processes', [])) ? 'selected' : '' }}>{{ $processes }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('processes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('processes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.processes_helper') }}</span>
                    </div>
                
                </div>

                <dic class="col-6">

                    <div class="form-group">
                        <label for="operations">{{ trans('cruds.activity.fields.operations') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('operations') ? 'is-invalid' : '' }}" name="operations[]" id="operations" multiple>
                            @foreach($operations as $id => $operations)
                                <option value="{{ $id }}" {{ in_array($id, old('operations', [])) ? 'selected' : '' }}>{{ $operations }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('operations'))
                            <div class="invalid-feedback">
                                {{ $errors->first('operations') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.operations_helper') }}</span>
                    </div>
                </dic>
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
        }
    );

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
                @foreach(session()->get("documents") as $document)
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
@endsection