@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.information.update", [$information->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.information.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.information.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $information->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.information.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $information->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.description_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-sm">


                    <div class="form-group">
                        <label for="processes">{{ trans('cruds.information.fields.processes') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
                            @foreach($processes as $id => $process)
                                <option value="{{ $id }}" {{ (in_array($id, old('processes', [])) || $information->processes->contains($id)) ? 'selected' : '' }}>{{ $process }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('processes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('processes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.processes_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label class="recommended" for="storage">{{ trans('cruds.information.fields.storage') }}</label>
                        <select class="form-control select2-free {{ $errors->has('storage') ? 'is-invalid' : '' }}" name="storage" id="storage">
                            @if (!$owner_list->contains(old('storage')))
                                <option> {{ old('storage') }}</option>'
                            @endif
                            @foreach($storage_list as $t)
                                <option {{ (old('storage') ? old('storage') : $information->storage) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('storage'))
                            <div class="invalid-feedback">
                                {{ $errors->first('storage') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.storage_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="owner">{{ trans('cruds.information.fields.owner') }}</label>
                        <select class="form-control select2-free {{ $errors->has('owner') ? 'is-invalid' : '' }}" name="owner" id="owner">
                            @if (!$owner_list->contains(old('owner')))
                                <option> {{ old('owner') }}</option>'
                            @endif
                            @foreach($owner_list as $t)
                                <option {{ (old('owner') ? old('owner') : $information->owner) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('owner'))
                            <div class="invalid-feedback">
                                {{ $errors->first('owner') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.owner_helper') }}</span>
                    </div>
                    <div class="form-group">
                        <label class="recommended" for="administrator">{{ trans('cruds.information.fields.administrator') }}</label>
                        <select class="form-control select2-free {{ $errors->has('administrator') ? 'is-invalid' : '' }}" name="administrator" id="administrator">
                            @if (!$owner_list->contains(old('administrator')))
                                <option> {{ old('administrator') }}</option>'
                            @endif
                            @foreach($administrator_list as $t)
                                <option {{ (old('administrator') ? old('administrator') : $information->administrator) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('administrator'))
                            <div class="invalid-feedback">
                                {{ $errors->first('administrator') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.administrator_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label class="recommended" for="sensitivity">{{ trans('cruds.information.fields.sensitivity') }}</label>
                        <select class="form-control select2-free {{ $errors->has('sensitivity') ? 'is-invalid' : '' }}" name="sensitivity" id="sensitivity">
                            @if (!$owner_list->contains(old('sensitivity')))
                                <option> {{ old('sensitivity') }}</option>'
                            @endif
                            @foreach($sensitivity_list as $t)
                                <option {{ (old('sensitivity') ? old('sensitivity') : $information->sensitivity) == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('sensitivity'))
                            <div class="invalid-feedback">
                                {{ $errors->first('sensitivity') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.sensitivity_helper') }}</span>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <table cellspacing="5" cellpadding="5" border="0" width='100%'>
                            <tr>
                                <td width='20%'>
                                    <label
                                    @if (auth()->user()->granularity>=2)
                                        class="recommended"
                                    @endif
                                    for="security_need">{{ trans('cruds.information.fields.security_need') }}</label>
                                </td>
                                <td align="right" width="10">
                                    <label for="security_need">C</label>
                                </td>
                                <td  width="120">
                                    <select class="form-control select2 risk {{ $errors->has('security_need_c') ? 'is-invalid' : '' }}" name="security_need_c" id="security_need_c">
                                        <option value="-1"></option>
                                        <option value="0" {{ ($information->security_need_c ? $information->security_need_c : old('security_need_c')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ ($information->security_need_c ? $information->security_need_c : old('security_need_c')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ ($information->security_need_c ? $information->security_need_c : old('security_need_c')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ ($information->security_need_c ? $information->security_need_c : old('security_need_c')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ ($information->security_need_c ? $information->security_need_c : old('security_need_c')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">I</label>
                                </td>
                                <td  width="120">
                                    <select class="form-control select2 risk {{ $errors->has('security_need_i') ? 'is-invalid' : '' }}" name="security_need_i" id="security_need_i">
                                        <option value="-1"></option>
                                        <option value="0" {{ ($information->security_need_i ? $information->security_need_i : old('security_need_i')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ ($information->security_need_i ? $information->security_need_i : old('security_need_i')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ ($information->security_need_i ? $information->security_need_i : old('security_need_i')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ ($information->security_need_i ? $information->security_need_i : old('security_need_i')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ ($information->security_need_i ? $information->security_need_i : old('security_need_i')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">D</label>
                                </td>
                                <td  width="120">
                                    <select class="form-control select2 risk {{ $errors->has('security_need_a') ? 'is-invalid' : '' }}" name="security_need_a" id="security_need_a">
                                        <option value="-1"></option>
                                        <option value="0" {{ ($information->security_need_a ? $information->security_need_a : old('security_need_a')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ ($information->security_need_a ? $information->security_need_a : old('security_need_a')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ ($information->security_need_a ? $information->security_need_a : old('security_need_a')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ ($information->security_need_a ? $information->security_need_a : old('security_need_a')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ ($information->security_need_a ? $information->security_need_a : old('security_need_a')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                                <td align="right">
                                    <label for="security_need">T</label>
                                </td>
                                <td  width="120">
                                    <select class="form-control select2 risk{{ $errors->has('security_need_t') ? 'is-invalid' : '' }}" name="security_need_t" id="security_need_t">
                                        <option value="-1"></option>
                                        <option value="0" {{ ($information->security_need_t ? $information->security_need_t : old('security_need_t')) == 0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                        <option value="1" {{ ($information->security_need_t ? $information->security_need_t : old('security_need_t')) == 1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                        <option value="2" {{ ($information->security_need_t ? $information->security_need_t : old('security_need_t')) == 2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                        <option value="3" {{ ($information->security_need_t ? $information->security_need_t : old('security_need_t')) == 3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                        <option value="4" {{ ($information->security_need_t ? $information->security_need_t : old('security_need_t')) == 4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        @if($errors->has('security_need'))
                            <div class="invalid-feedback">
                                {{ $errors->first('security_need') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.information.fields.security_need_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm">
                </div>
            </div>
            <div class="form-group">
                <label for="constraints">{{ trans('cruds.information.fields.constraints') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('constraints') ? 'is-invalid' : '' }}" name="constraints" id="constraints">{!! old('constraints', $information->constraints) !!}</textarea>
                @if($errors->has('constraints'))
                    <div class="invalid-feedback">
                        {{ $errors->first('constraints') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.information.fields.constraints_helper') }}</span>
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

    function template(data, container) {
      if (data.id==4) {
         return '\<span class="highRisk"\>'+data.text+'</span>';
      } else if (data.id==3) {
         return '\<span class="mediumRisk"\>'+data.text+'</span>';
      } else if (data.id==2) {
         return '\<span class="lowRisk"\>'+data.text+'</span>';
      } else if (data.id==1) {
         return '\<span class="veryLowRisk"\>'+data.text+'</span>';
      } else {
         return data.text;
      }
    }

    $('.risk').select2({
      templateSelection: template,
      escapeMarkup: function(m) {
          return m;
      }
    });
});
</script>

@endsection
