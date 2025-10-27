@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.relations.update", [$relation->id]) }}" enctype="multipart/form-data" id="relationForm">
    @method('PUT')
    @csrf
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.relation.title_singular') }}
    </div>

    <div class="card-body">
            <!---------------------------------------------------------------------------------------------------->
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.relation.fields.name') }}</label>
                        <input type="text" class="form-control" id="name" name="name" min="0" value="{{ old('name', $relation->name) }}" required autofocus/>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.name_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="type">{{ trans('cruds.relation.fields.type') }}</label>
                        <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                            @if (!$type_list->contains(old('type')))
                                <option> {{ old('type') }}</option>
                            @endif
                            @foreach($type_list as $t)
                                <option {{ (old('type') ? old('type') : $relation->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('type') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.type_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="attributes">{{ trans('cruds.relation.fields.attributes') }}</label>
                        <select class="form-control select2-free {{ $errors->has('patching_group') ? 'is-invalid' : '' }}" name="attributes[]" id="attributes[]" multiple>
                            @foreach($attributes_list as $a)
                                <option {{ ( (old('attributes')!=null) && in_array($a,old('attributes'))) || str_contains($relation->attributes, $a) ? 'selected' : '' }}>{{$a}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('attributes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('attributes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.attributes_helper') }}</span>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.relation.fields.reference') }}</label>
                        <input type="text" class="form-control" id="reference" name="reference" min="0" value="{{ old('reference', $relation->reference) }}">
                        @if($errors->has('reference'))
                            <div class="invalid-feedback">
                                {{ $errors->first('reference') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.reference_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.relation.fields.order_number') }}</label>
                        <input type="text" class="form-control" id="order_number" name="order_number" min="0" value="{{ old('order_number', $relation->order_number) }}">
                        @if($errors->has('order_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('order_number') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.order_number_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="responsible">{{ trans('cruds.application.fields.responsible') }}</label>
                        <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsibles[]" id="responsibles" multiple>
                            @foreach($responsibles_list as $resp)
                                <option {{ ( (old('responsibles')!=null) && in_array($resp,old('responsibles'))) || str_contains($relation->responsible, $resp) ? 'selected' : '' }}>{{$resp}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.responsible_helper') }}</span>
                    </div>
                <span class="help-block">{{ trans('cruds.relation.fields.name_helper') }}</span>
            </div>
        </div>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="required" for="source_id">{{ trans('cruds.relation.fields.source') }}</label>
                        <select class="form-control select2 {{ $errors->has('source') ? 'is-invalid' : '' }}" name="source_id" id="source_id" required>
                            <option value="">{{ trans('global.pleaseSelect') }}</option>
                            @foreach($sources as $source)
                                <option value="{{ $source->id }}" {{ ($relation->source ? $relation->source->id : old('source_id')) == $source->id ? 'selected' : '' }}>{{ $source->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('source'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.source_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label class="required" for="destination_id">{{ trans('cruds.relation.fields.destination') }}</label>
                        <select class="form-control select2 {{ $errors->has('destination') ? 'is-invalid' : '' }}" name="destination_id" id="destination_id" required>
                            <option value="">{{ trans('global.pleaseSelect') }}</option>
                            @foreach($destinations as $destination)
                                <option value="{{ $destination->id }}" {{ ($relation->destination ? $relation->destination->id : old('destination_id')) == $destination->id ? 'selected' : '' }}>{{ $destination->name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('destination'))
                            <div class="invalid-feedback">
                                {{ $errors->first('destination') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.destination_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.relation.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $relation->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.relation.fields.description_helper') }}</span>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card">
        <div class="card-header">
            {{ trans('cruds.relation.fields.contract_title') }}
        </div>
    <!---------------------------------------------------------------------------------------------------->
        <div class="card-body">
            <div class="row">
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="start_date">{{ trans('cruds.relation.fields.start_date') }}</label>
                        <input class="form-control" type="date" name="start_date" id="start_date" value="{{ old('start_date', $relation->start_date) }}">
                        <span class="help-block">{{ trans('cruds.relation.fields.start_date_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label for="end_date">{{ trans('cruds.relation.fields.end_date') }}</label>
                        <input class="form-control" type="date" name="end_date" id="end_date" value="{{ old('end_date', $relation->end_date) }}">
                        <span class="help-block">{{ trans('cruds.relation.fields.end_date_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-check">
                        <label for="crypted">{{ trans('cruds.relation.fields.active') }}</label>
                        <div class="form-switch">
                          <input class="form-check-input" type="checkbox" id="active" name="active" {{ $relation->active ? "checked" : "" }}>
                          <label class="form-check-label" for="active">{{ trans('cruds.relation.fields.active_helper') }}</label>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="recommended" for="importance">{{ trans('cruds.relation.fields.importance') }}</label>
                        <select class="form-control select2 risk {{ $errors->has('importance') ? 'is-invalid' : '' }}" name="importance" id="importance">
                            <option value="0"></option>
                            <option value="1" {{ old('importance',$relation->importance) == 1 ? 'selected' : '' }}>{{ trans('cruds.relation.fields.importance_level.low') }}</option>
                            <option value="2" {{ old('importance',$relation->importance) == 2 ? 'selected' : '' }}>{{ trans('cruds.relation.fields.importance_level.medium') }}</option>
                            <option value="3" {{ old('importance',$relation->importance) == 3 ? 'selected' : '' }}>{{ trans('cruds.relation.fields.importance_level.high') }}</option>
                            <option value="4" {{ old('importance',$relation->importance) == 4 ? 'selected' : '' }}>{{ trans('cruds.relation.fields.importance_level.critical') }}</option>
                        </select>
                        @if($errors->has('importance'))
                            <div class="invalid-feedback">
                                {{ $errors->first('importance') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.importance_helper') }}</span>
                    </div>

                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="col-sm-4">
                <table class="table-narrow" id="dynamicAddRemove">
                    <tr>
                        <th>{{ trans('cruds.relation.fields.date') }}</th>
                        <th>{{ trans('cruds.relation.fields.value') }}</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td>
                            <div class="col">
                            <input type="date" class="form-control" id="inputDateField"/>
                            </div>
                        </td>
                        <td>
                            <input type="text" class="form-control" id="inputValueField"/>
                        </td>
                        <td>
                            <button type="button" id="dynamic-ar" class="btn btn-outline-primary">Add</button>
                        </td>
                    </tr>
                    @foreach($values as $value)
                        <tr>
                        <td>
                            <div class="col">
                            <input type="date" name="dates[]" value="{{ $value->date_price }}" class="form-control" />
                            </div>
                        </td>
                        <td>
                            <input type="text" name="values[]" value="{{ $value->price }}" class="form-control" />
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card">
        <div class="card-header">
            Commentaires / Documents
        </div>
        <div class="card-body">
        <div class="row">
                <div class="col-lg">
                    <div class="form-group">
                        <label for="comments">{{ trans('cruds.relation.fields.comments') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('comments') ? 'is-invalid' : '' }}" name="comments" id="comments">{!! old('comments', $relation->comments) !!}</textarea>
                        @if($errors->has('comments'))
                            <div class="invalid-feedback">
                                {{ $errors->first('comments') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.comments_helper') }}</span>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="documents">{{ trans('cruds.dataProcessing.fields.documents') }}</label>
                        <div class="dropzone dropzone-previews" id="dropzoneFileUpload"></div>
                        @if($errors->has('documents'))
                            <div class="invalid-feedback">
                                {{ $errors->first('documents') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.relation.fields.documents_helper') }}</span>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.relations.index') }}">
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

     //=============================================================================
    var dynamicInputRow = {{ $values->count() }};

    // Initialiser Dropzone
    var image_uploader = new Dropzone("#dropzoneFileUpload", {
        url: '/admin/documents/store',
        headers: { 'x-csrf-token': '{{csrf_token()}}' },
        paramName: 'file',
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
                @foreach($relation->documents as $document)
                    { name: "{{ $document->filename }}", size: {{ $document->size }}, id: {{ $document->id }} },
                @endforeach
            ];
            for (i = 0; i < existingFiles.length; i++) {
                this.emit("addedfile", existingFiles[i]);
                this.emit("complete", existingFiles[i]);
                }
            }
        });

        document.onpaste = function(event) {
          const items = (event.clipboardData || event.originalEvent.clipboardData).items;
          items.forEach((item) => {
          	console.log(item.kind);
            if (item.kind === 'file') {
              	// adds the file to your dropzone instance
              	image_uploader.addFile(item.getAsFile())
            	}
          	})
        }

       //-----------------------------------------
        $("#dynamic-ar").click(function () {
            if (($("#inputDateField").val()!='')&&($("#inputValueField").val()!='')) {
                input = $("#dynamicAddRemove")
                    .append(
                        '<tr>\
                        <td><div class="col"><input type="date" name="dates[]" value="' + $("#inputDateField").val() + '" class="form-control date" /></div></td>\
                        <td><input type="text" name="values[]" value="' + $("#inputValueField").val() + '" class="form-control" /></td>\
                        <td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>\
                        </tr>');
                $("#inputDateField").val('');
                $("#inputValueField").val('');
            }
        });

        $(document).on('click', '.remove-input-field', function () {
            $(this).parents('tr').remove();
        });

    });
</script>

@endsection
