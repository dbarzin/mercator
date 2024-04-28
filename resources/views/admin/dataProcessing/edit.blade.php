@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.dataProcessing.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.data-processings.update", [$dataProcessing->id]) }}" enctype="multipart/form-data" >
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.dataProcessing.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" maxlength=32 value="{{ old('name', $dataProcessing->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.dataProcessing.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $dataProcessing->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.description_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="responsible">{{ trans('cruds.dataProcessing.fields.responsible') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsible" id="responsible">{!! old('responsible', $dataProcessing->responsible) !!}</textarea>
                @if($errors->has('responsible'))
                    <div class="invalid-feedback">
                        {{ $errors->first('responsible') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.responsible_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="purpose">{{ trans('cruds.dataProcessing.fields.purpose') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('purpose') ? 'is-invalid' : '' }}" name="purpose" id="purpose">{!! old('purpose', $dataProcessing->purpose) !!}</textarea>
                @if($errors->has('purpose'))
                    <div class="invalid-feedback">
                        {{ $errors->first('purpose') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.purpose_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="categories">{{ trans('cruds.dataProcessing.fields.categories') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('categories') ? 'is-invalid' : '' }}" name="categories" id="categories">{!! old('categories', $dataProcessing->categories) !!}</textarea>
                @if($errors->has('categories'))
                    <div class="invalid-feedback">
                        {{ $errors->first('categories') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.categories_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="recipients">{{ trans('cruds.dataProcessing.fields.recipients') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('recipients') ? 'is-invalid' : '' }}" name="recipients" id="recipients">{!! old('recipients', $dataProcessing->recipients) !!}</textarea>
                @if($errors->has('recipients'))
                    <div class="invalid-feedback">
                        {{ $errors->first('recipients') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.recipients_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="transfert">{{ trans('cruds.dataProcessing.fields.transfert') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('transfert') ? 'is-invalid' : '' }}" name="transfert" id="transfert">{!! old('transfert', $dataProcessing->transfert) !!}</textarea>
                @if($errors->has('transfert'))
                    <div class="invalid-feedback">
                        {{ $errors->first('transfert') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.transfert_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="retention">{{ trans('cruds.dataProcessing.fields.retention') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('retention') ? 'is-invalid' : '' }}" name="retention" id="retention">{!! old('retention', $dataProcessing->retention) !!}</textarea>
                @if($errors->has('retention'))
                    <div class="invalid-feedback">
                        {{ $errors->first('retention') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.retention_helper') }}</span>
            </div>


            <div class="form-group">
                <label for="operations">{{ trans('cruds.dataProcessing.fields.processes') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
                    @foreach($processes as $process)
                        <option value="{{ $process->id }}" {{ (in_array($process->id, old('processes', [])) || $dataProcessing->processes->contains($process->id)) ? 'selected' : '' }}>{{ $process->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('processes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('processes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.processes_helper') }}</span>
            </div>


            <div class="form-group">
                <label for="applications">{{ trans('cruds.dataProcessing.fields.applications') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                    @foreach($applications as $app)
                        <option value="{{ $app->id }}" {{ (in_array($app->id, old('processes', [])) || $dataProcessing->applications->contains($app->id)) ? 'selected' : '' }}>{{ $app->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('applications'))
                    <div class="invalid-feedback">
                        {{ $errors->first('applications') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.applications_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="informations">{{ trans('cruds.dataProcessing.fields.information') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('informations') ? 'is-invalid' : '' }}" name="informations[]" id="informations" multiple>
                    @foreach($informations as $info)
                        <option value="{{ $info->id }}" {{ (in_array($info->id, old('processes', [])) || $dataProcessing->informations->contains($info->id)) ? 'selected' : '' }}>{{ $info->name }}</option>
                    @endforeach
                </select>
                @if($errors->has('informations'))
                    <div class="invalid-feedback">
                        {{ $errors->first('informations') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.dataProcessing.fields.information_helper') }}</span>
            </div>

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
                @foreach($dataProcessing->documents as $document)
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
