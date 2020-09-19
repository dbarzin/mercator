@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.database.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.databases.update", [$database->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.database.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $database->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.database.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.database.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $database->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.database.fields.description_helper') }}</span>
            </div>

          <div class="row">
            <div class="col-sm">

                <div class="form-group">
                    <label for="entities">{{ trans('cruds.database.fields.entities') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('entities') ? 'is-invalid' : '' }}" name="entities[]" id="entities" multiple>
                        @foreach($entities as $id => $entities)
                            <option value="{{ $id }}" {{ (in_array($id, old('entities', [])) || $database->entities->contains($id)) ? 'selected' : '' }}>{{ $entities }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('entities'))
                        <div class="invalid-feedback">
                            {{ $errors->first('entities') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.database.fields.entities_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="entity_resp_id">{{ trans('cruds.database.fields.entity_resp') }}</label>
                    <select class="form-control select2 {{ $errors->has('entity_resp') ? 'is-invalid' : '' }}" name="entity_resp_id" id="entity_resp_id">
                        @foreach($entity_resps as $id => $entity_resp)
                            <option value="{{ $id }}" {{ ($database->entity_resp ? $database->entity_resp->id : old('entity_resp_id')) == $id ? 'selected' : '' }}>{{ $entity_resp }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('entity_resp'))
                        <div class="invalid-feedback">
                            {{ $errors->first('entity_resp') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.database.fields.entity_resp_helper') }}</span>
                </div>
            </div>
            <div class="col-sm">

                <div class="form-group">
                    <label for="applications">{{ trans('cruds.database.fields.applications') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                        @foreach($applications as $id => $applications)
                            <option value="{{ $id }}" {{ (in_array($id, old('applications', [])) || $database->databasesMApplications->contains($id)) ? 'selected' : '' }}>{{ $applications }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('applications'))
                        <div class="invalid-feedback">
                            {{ $errors->first('applications') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.database.fields.applications_helper') }}</span>
                </div>


                <div class="form-group">
                    <label for="responsible">{{ trans('cruds.database.fields.responsible') }}</label>
                    <select class="form-control select2-free {{ $errors->has('responsible') ? 'is-invalid' : '' }}" name="responsible" id="responsible">
                        @if (!$external_list->contains(old('responsible')))
                            <option> {{ old('responsible') }}</option>'
                        @endif
                        @foreach($responsible_list as $t)
                            <option {{ (old('responsible') ? old('responsible') : $database->responsible) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.database.fields.responsible_helper') }}</span>
                </div>
                
            </div>
            <div class="col-sm">

                <div class="form-group">
                    <label for="informations">{{ trans('cruds.database.fields.informations') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('informations') ? 'is-invalid' : '' }}" name="informations[]" id="informations" multiple>
                        @foreach($informations as $id => $informations)
                            <option value="{{ $id }}" {{ (in_array($id, old('informations', [])) || $database->informations->contains($id)) ? 'selected' : '' }}>{{ $informations }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('informations'))
                        <div class="invalid-feedback">
                            {{ $errors->first('informations') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.database.fields.informations_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="type">{{ trans('cruds.database.fields.type') }}</label>
                    <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                        @if (!$type_list->contains(old('type')))
                            <option> {{ old('type') }}</option>'
                        @endif
                        @foreach($type_list as $t)
                            <option {{ (old('type') ? old('type') : $database->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.database.fields.type_helper') }}</span>
                </div>
                </div>
            </div>
            <div class="form-group">
                <label for="security_need">{{ trans('cruds.database.fields.security_need') }}</label>

                <select class="form-control select2 {{ $errors->has('security_need') ? 'is-invalid' : '' }}" name="security_need" id="security_need">
                    <option value="0" {{ ($database->security_need ? $database->security_need : old('security_need')) == 0 ? 'selected' : '' }}></option>
                    <option value="1" {{ ($database->security_need ? $database->security_need : old('security_need')) == 1 ? 'selected' : '' }}>Public</option>
                    <option value="2" {{ ($database->security_need ? $database->security_need : old('security_need')) == 2 ? 'selected' : '' }}>Internal</option>
                    <option value="3" {{ ($database->security_need ? $database->security_need : old('security_need')) == 3 ? 'selected' : '' }}>Confidential</option>
                    <option value="4" {{ ($database->security_need ? $database->security_need : old('security_need')) == 4 ? 'selected' : '' }}>Secret</option>
                </select>

                @if($errors->has('security_need'))
                    <div class="invalid-feedback">
                        {{ $errors->first('security_need') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.database.fields.security_need_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="external">{{ trans('cruds.database.fields.external') }}</label>
                <select class="form-control select2-free {{ $errors->has('external') ? 'is-invalid' : '' }}" name="external" id="external">
                    @if (!$external_list->contains(old('external')))
                        <option> {{ old('external') }}</option>'
                    @endif
                    @foreach($external_list as $t)
                        <option {{ (old('external') ? old('external') : $database->external) == $t ? 'selected' : '' }}>{{$t}}</option>
                    @endforeach
                </select>
                @if($errors->has('external'))
                    <div class="invalid-feedback">
                        {{ $errors->first('external') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.database.fields.external_helper') }}</span>
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

  $(".select2-free").select2({
        placeholder: "{{ trans('global.pleaseSelect') }}",
        allowClear: true,
        tags: true
    }) 

});
</script>

@endsection