@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.relation.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.relations.update", [$relation->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <!---------------------------------------------------------------------------------------------------->
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.relation.fields.name') }}</label>
                        <select class="form-control select2-free {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="name">
                            @if (!$name_list->contains(old('name')))
                                <option> {{ old('name') }}</option>'
                            @endif
                            @foreach($name_list as $t)
                                <option {{ (old('name') ? old('name') : $relation->name) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
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
                                <option> {{ old('type') }}</option>'
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
                        <label class="recommended" for="attributes">{{ trans('cruds.relation.fields.attributes') }}</label>
                        <select class="form-control select2-free {{ $errors->has('patching_group') ? 'is-invalid' : '' }}" name="attributes[]" id="attributes[]" multiple>
                            @foreach($attributes_list as $a)
                                <option {{ str_contains(old('attributes') ? old('attributes') : $relation->attributes, $a) ? 'selected' : '' }}>{{$a}}</option>
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
                            <option {{ str_contains($relation->responsible ,$resp) ? 'selected' : '' }}>{{$resp}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('responsible'))
                        <div class="invalid-feedback">
                            {{ $errors->first('responsible') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.application.fields.responsible_helper') }}</span>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="required" for="source_id">{{ trans('cruds.relation.fields.source') }}</label>
                        <select class="form-control select2 {{ $errors->has('source') ? 'is-invalid' : '' }}" name="source_id" id="source_id" required>
                            @foreach($sources as $id => $source)
                                <option value="{{ $id }}" {{ ($relation->source ? $relation->source->id : old('source_id')) == $id ? 'selected' : '' }}>{{ $source }}</option>
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
                            @foreach($destinations as $id => $destination)
                                <option value="{{ $id }}" {{ ($relation->destination ? $relation->destination->id : old('destination_id')) == $id ? 'selected' : '' }}>{{ $destination }}</option>
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
            <!---------------------------------------------------------------------------------------------------->
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
            <!---------------------------------------------------------------------------------------------------->
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="install_date">{{ trans('cruds.relation.fields.start_date') }}</label>
                        <input class="form-control date" type="text" name="start_date" id="start_date" value="{{ old('start_date', $relation->start_date) }}">
                        <span class="help-block">{{ trans('cruds.relation.fields.start_date_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="install_date">{{ trans('cruds.relation.fields.end_date') }}</label>
                        <input class="form-control date" type="text" name="end_date" id="start_date" value="{{ old('end_date', $relation->end_date) }}">
                        <span class="help-block">{{ trans('cruds.relation.fields.end_date_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                    <div class="form-group">
                        <label for="crypted">{{ trans('cruds.relation.fields.active') }}</label>
                        <div class="form-check form-switch">
                          <input class="form-check-input" type="checkbox" id="active" name="active" value="1" {{ $relation->active ? "checked" : "" }}>
                          <label class="form-check-label" for="active">{{ trans('cruds.relation.fields.active_helper') }}</label>
                        </div>
                    </div>
                </div>
            </div>

            <!---------------------------------------------------------------------------------------------------->
            <div class="row">
                <div class="col-lg">



            <div class="form-group">
                <table cellspacing="5" cellpadding="5" border="0" width='40%'>
                    <tr>
                        <td width='20%'>
                            <label class="recommended" for="security_need">{{ trans('cruds.database.fields.security_need') }}</label>
                        </td>
                        <td align="right" width="10">
                            <label for="security_need">C</label>
                        </td>
                        <td width="120">
                            <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}" name="security_need_c" id="security_need_c">
                                <option value="-1" {{ ($relation->security_need_c ? $relation->security_need_c : old('security_need_c')) == -1 ? 'selected' : '' }}></option>
                                <option value="0" {{ ($relation->security_need_c ? $relation->security_need_c : old('security_need_c')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                <option value="1" {{ ($relation->security_need_c ? $relation->security_need_c : old('security_need_c')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                <option value="2" {{ ($relation->security_need_c ? $relation->security_need_c : old('security_need_c')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                <option value="3" {{ ($relation->security_need_c ? $relation->security_need_c : old('security_need_c')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                <option value="4" {{ ($relation->security_need_c ? $relation->security_need_c : old('security_need_c')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                            </select>
                        </td>
                        <td align="right">
                            <label for="security_need">I</label>
                        </td>
                        <td width="120">
                            <select class="form-control select2 risk {{ $errors->has('security_need_i') ? 'is-invalid' : '' }}" name="security_need_i" id="security_need_i">
                                <option value="-1" {{ ($relation->security_need_i ? $relation->security_need_i : old('security_need_i')) == -1 ? 'selected' : '' }}></option>
                                <option value="0" {{ ($relation->security_need_i ? $relation->security_need_i : old('security_need_i')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                <option value="1" {{ ($relation->security_need_i ? $relation->security_need_i : old('security_need_i')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                <option value="2" {{ ($relation->security_need_i ? $relation->security_need_i : old('security_need_i')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                <option value="3" {{ ($relation->security_need_i ? $relation->security_need_i : old('security_need_i')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                <option value="4" {{ ($relation->security_need_i ? $relation->security_need_i : old('security_need_i')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                            </select>
                        </td>
                        <td align="right">
                            <label for="security_need">D</label>
                        </td>
                        <td width="120">
                            <select class="form-control select2 risk {{ $errors->has('security_need_a') ? 'is-invalid' : '' }}" name="security_need_a" id="security_need_a">
                                <option value="-1" {{ ($relation->security_need_a ? $relation->security_need_a : old('security_need_a')) == -1 ? 'selected' : '' }}></option>
                                <option value="0" {{ ($relation->security_need_a ? $relation->security_need_a : old('security_need_a')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                <option value="1" {{ ($relation->security_need_a ? $relation->security_need_a : old('security_need_a')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                <option value="2" {{ ($relation->security_need_a ? $relation->security_need_a : old('security_need_a')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                <option value="3" {{ ($relation->security_need_a ? $relation->security_need_a : old('security_need_a')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                <option value="4" {{ ($relation->security_need_a ? $relation->security_need_a : old('security_need_a')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                            </select>
                        </td>
                        <td align="right">
                            <label for="security_need">T</label>
                        </td>
                        <td width="120">
                            <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}" name="security_need_t" id="security_need_t">
                                <option value="-1" {{ ($relation->security_need_t ? $relation->security_need_t : old('security_need_t')) == -1 ? 'selected' : '' }}></option>
                                <option value="0" {{ ($relation->security_need_t ? $relation->security_need_t : old('security_need_t')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                <option value="1" {{ ($relation->security_need_t ? $relation->security_need_t : old('security_need_t')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                <option value="2" {{ ($relation->security_need_t ? $relation->security_need_t : old('security_need_t')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                <option value="3" {{ ($relation->security_need_t ? $relation->security_need_t : old('security_need_t')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                <option value="4" {{ ($relation->security_need_t ? $relation->security_need_t : old('security_need_t')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                            </select>
                        </td>
                    </tr>
                </table>
                @if($errors->has('security_need'))
                    <div class="invalid-feedback">
                        {{ $errors->first('security_need') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.database.fields.security_need_helper') }}</span>
            </div>



                </div>
            </div>

            <!---------------------------------------------------------------------------------------------------->
            <div class="row">
                <div class="col-lg">
                    <div class="form-group">
                        <label class="recommended" for="comments">{{ trans('cruds.relation.fields.comments') }}</label>
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
                        <label class="recommended" for="controls">{{ trans('cruds.dataProcessing.fields.documents') }}</label>
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

    $('.risk').select2({
        templateSelection: function(data, container) {
            if (data.id==4)
                 return '\<span class="highRisk"\>'+data.text+'</span>';
            else if (data.id==3)
                 return '\<span class="mediumRisk"\>'+data.text+'</span>';
            else if (data.id==2)
                 return '\<span class="lowRisk"\>'+data.text+'</span>';
            else if (data.id==1)
                 return '\<span class="veryLowRisk"\>'+data.text+'</span>';
            else
                 return data.text;
        },
        escapeMarkup: function(m) {
          return m;
      }
    });
});
</script>

@endsection
