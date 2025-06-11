@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.entities.update", [$entity->id]) }}" enctype="multipart/form-data">
@method('PUT')
@csrf
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.entity.title_singular') }}
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label class="required" for="name">{{ trans('cruds.entity.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $entity->name) }}" required maxlength="64" autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.name_helper') }}</span>
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-group">
                    <label for="entity_type">{{ trans('cruds.entity.fields.entity_type') }}</label>
                    <select class="form-control select2-free {{ $errors->has('entity_type') ? 'is-invalid' : '' }}"
                               name="entity_type" id="entity_type">
                        @if (!$entityTypes->contains(old('entity_type')))
                            <option> {{ old('entity_type') }}</option>'
                        @endif
                        @foreach($entityTypes as $t)
                            <option {{ (old('entity_type') ? old('entity_type') : $entity->entity_type) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    <span class="help-block">{{ trans('cruds.entity.fields.entity_type_helper') }}</span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label for="processes">{{ trans('cruds.entity.fields.parent_entity') }}</label>
                    <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="parent_entity_id" id="parent_entity_id">
                        <option></option>
                        @foreach($entities as $id => $name)
                            <option value="{{ $id }}" {{ old('entity',$entity->parent_entity_id)==$id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('processes'))
                        <span class="text-danger">{{ $errors->first('parent_entity') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.parent_entity_helper') }}</span>
                </div>
            </div>

            <div class="col-md-1">
                <br>
        	    <div class="form-group">
            		<div class="form-check form-switch">
                        <input name="is_external" id='is_external' type="checkbox" class="form-check-input" {{ old('is_external',$entity->is_external) ? 'checked' : '' }}>
                        <label for="is_external">{{ trans('cruds.entity.fields.is_external') }}</label>
            		</div>
            		@if($errors->has('is_external'))
        		    <div class="invalid-feedback">
        			{{ $errors->first('is_external') }}
        		    </div>
            		@endif
            		<span class="help-block">{{ trans('cruds.entity.fields.is_external_helper') }}</span>
        	    </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-9">
                <div class="form-group">
                    <label class="recommended1" for="description">{{ trans('cruds.entity.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $entity->description) !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.description_helper') }}</span>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label for="iconSelect">{{ trans('global.icon_select') }}</label>
                    <select id="iconSelect" name="iconSelect" class="form-control"></select>
                </div>
                <div class="form-group">
                    <input type="file" id="iconFile" name="iconFile" accept="image/png" />
                </div>
            </div>
        </div>

        <div class="form-group">
            <label class="recommended1" for="contact_point">{{ trans('cruds.entity.fields.contact_point') }}</label>
            <textarea class="form-control ckeditor {{ $errors->has('contact_point') ? 'is-invalid' : '' }}" name="contact_point" id="contact_point">{!! old('contact_point', $entity->contact_point) !!}</textarea>
            @if($errors->has('contact_point'))
                <div class="invalid-feedback">
                    {{ $errors->first('contact_point') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.entity.fields.contact_point_helper') }}</span>
        </div>
        <div class="form-group">
            <label class="recommended1" for="security_level">{{ trans('cruds.entity.fields.security_level') }}</label>
            <textarea class="form-control ckeditor {{ $errors->has('security_level') ? 'is-invalid' : '' }}" name="security_level" id="security_level">{!! old('security_level', $entity->security_level) !!}</textarea>
            @if($errors->has('security_level'))
                <div class="invalid-feedback">
                    {{ $errors->first('security_level') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.entity.fields.security_level_helper') }}</span>
        </div>


        <div class="row">
            <div class="col-md-6">

                <div class="form-group">
                    <label for="applications">{{ trans('cruds.entity.fields.applications_resp') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="respApplications[]" id="respApplications" multiple>
                        @foreach($applications as $id => $application)
                            <option value="{{ $id }}" {{ (in_array($id, old('respApplications', [])) || $entity->respApplications->contains($id)) ? 'selected' : '' }}>{{ $application }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('respApplications'))
                        <span class="text-danger">{{ $errors->first('respApplications') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.applications_resp_helper') }}</span>
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label for="databases">{{ trans('cruds.entity.fields.databases_resp') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('databases') ? 'is-invalid' : '' }}" name="databases[]" id="databases" multiple>
                        @foreach($databases as $id => $name)
                            <option value="{{ $id }}" {{ (in_array($id, old('databases', [])) || $entity->databases->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('databases'))
                        <span class="text-danger">{{ $errors->first('databases') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.databases_resp_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="recommended1" for="processes">{{ trans('cruds.entity.fields.processes') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
                        @foreach($processes as $id => $identificateur)
                            <option value="{{ $id }}" {{ (in_array($id, old('processes', [])) || $entity->processes->contains($id)) ? 'selected' : '' }}>{{ $identificateur }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('processes'))
                        <span class="text-danger">{{ $errors->first('processes') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.entity.fields.processes_helper') }}</span>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group">
    <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.entities.index') }}">
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
                img: '/images/application.png',
                imgWidth: '120px',
                imgHeight: '120px',
                selected: {{ $entity->icon_id === null ? "true" : "false"}},
            },
            @foreach($icons as  $icon)
            {
                value: '{{ $icon }}',
                img: '{{ route('admin.documents.show', $icon) }}',
                imgWidth: '120px',
                imgHeight: '120px',
                selected: {{ $entity->icon_id === $icon ? "true" : "false" }},
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
