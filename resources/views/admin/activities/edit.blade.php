@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.activities.update", [$activity->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.activity.title_singular') }}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-6">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.activity.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $activity->name) }}" maxlength=64 required autofocus/>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.name_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.activity.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $activity->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.description_helper') }}</span>
            </div>

            <div class='row'>
                <div class='col-6'>

                    <div class="form-group">
                        <label for="operations">{{ trans('cruds.activity.fields.processes') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('processes') ? 'is-invalid' : '' }}" name="processes[]" id="processes" multiple>
                            @foreach($processes as $id => $name)
                                <option value="{{ $id }}" {{ (in_array($id, old('processes', [])) || $activity->processes->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
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
                <div class='col-6'>

                    <div class="form-group">
                        <label for="operations">{{ trans('cruds.activity.fields.operations') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('operations') ? 'is-invalid' : '' }}" name="operations[]" id="operations" multiple>
                            @foreach($operations as $id => $operation)
                                <option value="{{ $id }}" {{ (in_array($id, old('operations', [])) || $activity->operations->contains($id)) ? 'selected' : '' }}>{{ $operation }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('operations'))
                            <div class="invalid-feedback">
                                {{ $errors->first('operations') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.operations_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class="col">
                    <div class="form-group">
                        <label for="applications">{{ trans('cruds.process.fields.applications') }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications[]" multiple>
                            @foreach($applications as $id => $application)
                                <option value="{{ $id }}" {{ (in_array($id, old('applications', [])) || $activity->applications->contains($id)) ? 'selected' : '' }}>{{ $application }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('applications'))
                            <div class="invalid-feedback">
                                {{ $errors->first('applications') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.process.fields.applications_helper') }}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-header">
            {{ trans('cruds.activity.bia') }}
        </div>
        <div class="card-body">
            <div class='row'>
                <div class="col-3">
                    <div class="form-group">
                        <label for="recovery_time_objective">{{ trans('cruds.activity.fields.recovery_time_objective') }}</label>
                        <input class="form-control {{ $errors->has('recovery_time_objective') ? 'is-invalid' : '' }}" type="text" name="recovery_time_objective" id="recovery_time_objective" value="{{ old('name', $activity->recovery_time_objective) }}" placeholder="HH:MM" maxlength=5/>
                        @if($errors->has('recovery_time_objective'))
                            <div class="invalid-feedback">
                                {{ $errors->first('recovery_time_objective') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.recovery_time_objective_helper') }}</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="recovery_point_objective">{{ trans('cruds.activity.fields.recovery_point_objective') }}</label>
                        <input class="form-control {{ $errors->has('recovery_point_objective') ? 'is-invalid' : '' }}" type="text" name="recovery_point_objective" id="recovery_point_objective" value="{{ old('name', $activity->recovery_point_objective) }}" placeholder="HH:MM" maxlength=5/>
                        @if($errors->has('recovery_point_objective'))
                            <div class="invalid-feedback">
                                {{ $errors->first('recovery_point_objective') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.recovery_point_objective_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class='row'>
                <div class="col-3">
                    <div class="form-group">
                        <label for="maximum_tolerable_downtime">{{ trans('cruds.activity.fields.maximum_tolerable_downtime') }}</label>
                        <input class="form-control {{ $errors->has('maximum_tolerable_downtime') ? 'is-invalid' : '' }}" type="text" name="maximum_tolerable_downtime" id="maximum_tolerable_downtime" value="{{ old('name', $activity->maximum_tolerable_downtime) }}" placeholder="HH:MM" maxlength=5/>
                        @if($errors->has('maximum_tolerable_downtime'))
                            <div class="invalid-feedback">
                                {{ $errors->first('maximum_tolerable_downtime') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.maximum_tolerable_downtime_helper') }}</span>
                    </div>
                </div>
                <div class="col-3">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.activity.fields.maximum_tolerable_data_loss') }}</label>
                        <input class="form-control {{ $errors->has('maximum_tolerable_data_loss') ? 'is-invalid' : '' }}" type="text" name="maximum_tolerable_data_loss" id="maximum_tolerable_data_loss" value="{{ old('name', $activity->maximum_tolerable_data_loss) }}" placeholder="HH:MM" maxlength=5/>
                        @if($errors->has('maximum_tolerable_data_loss'))
                            <div class="invalid-feedback">
                                {{ $errors->first('maximum_tolerable_data_loss') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.activity.fields.maximum_tolerable_data_loss_helper') }}</span>
                    </div>
                </div>
            </div>
            <!---------------------------------------------------------------------------------------------------->
            <div class="col-8">
                <table class="table-narrow" id="dynamicAddRemove">
                    <tr>
                        <th>{{ trans('cruds.activity.fields.impact_type') }}</th>
                        <th>{{ trans('cruds.activity.fields.gravity') }}</th>
                        <th></th>
                    </tr>
                    <tr>
                        <td width="200px">
                            <div class="col">
                                <select class="form-control select2-free {{ $errors->has('impact_type') ? 'is-invalid' : '' }}" name="impact_type" id="inputTypeField">
                                    <option></option>
                                    @foreach($types as $type)
                                    <option>{{$type}}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">{{ trans('cruds.activity.fields.impact_type_helper') }}</span>
                            </div>
                        </td>
                        <td>
                            <select class="form-control select2 risk" id="inputValueField">
                                <option value="-1"></option>
                                <option value="0">{{ trans('global.none') }}</option>
                                <option value="1">{{ trans('global.low') }}</option>
                                <option value="2">{{ trans('global.medium') }}</option>
                                <option value="3">{{ trans('global.strong') }}</option>
                                <option value="4">{{ trans('global.very_strong') }}</option>
                            </select>
                            <span class="help-block">{{ trans('cruds.activity.fields.gravity_helper') }}</span>
                        </td>
                        <td>
                            <button type="button" id="dynamic-ar" class="btn btn-outline-primary">Add</button>
                        </td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                    </td>
                    <tr>
                        <th colspan="2">
                            Liste des impactes
                        </th>
                    </tr>
                    @foreach($activity->impacts as $impact)
                        <tr>
                        <td>
                            <div class="col">
                                <select class="form-control select2-free {{ $errors->has('patching_group') ? 'is-invalid' : '' }}" name="impact_types[]" id='type{{ $loop->iteration }}'>
                                @foreach($types as $type)
                                    <option {{ $impact->impact_type === $type ? 'selected' : '' }}>{{$type}}</option>
                                @endforeach
                                </select>
                            </div>
                        </td>
                        <td>
                            <select class="form-control select2 risk" name="severities[]">'
                                <option value="-1"></option>'
                                <option value="0" {{ $impact->severity==0 ? 'selected' : '' }}>{{ trans('global.none') }}</option>
                                <option value="1" {{ $impact->severity==1 ? 'selected' : '' }}>{{ trans('global.low') }}</option>
                                <option value="2" {{ $impact->severity==2 ? 'selected' : '' }}>{{ trans('global.medium') }}</option>
                                <option value="3" {{ $impact->severity==3 ? 'selected' : '' }}>{{ trans('global.strong') }}</option>
                                <option value="4" {{ $impact->severity==4 ? 'selected' : '' }}>{{ trans('global.very_strong') }}</option>
                            </select>
                        </td>
                        <td>
                            <button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.activities.index') }}">
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

       //-----------------------------------------
        $("#dynamic-ar").click(function () {
            console.log($("#inputValueField").val());
            if (($("#inputTypeField").val()!='')&&($("#inputValueField").val()!=-1)) {
                input = $("#dynamicAddRemove")
                    .append(
                        '<tr>' +
                        '<td><div class="col"><input type="impact_type" name="impact_types[]" value="' + $("#inputTypeField").val() + '" class="form-control" /></div></td>'+
                        '<td>'+
                        '<select class="form-control select2 risk" name="severities[]">'+
                        '    <option value="-1"></option>'+
                        '    <option value="0" '+ ($("#inputValueField").val()=='0' ? 'selected' :'') +'>{{ trans('global.none') }}</option>'+
                        '    <option value="1" '+ ($("#inputValueField").val()=='1' ? 'selected' :'') +'>{{ trans('global.low') }}</option>'+
                        '    <option value="2" '+ ($("#inputValueField").val()=='2' ? 'selected' :'') +'>{{ trans('global.medium') }}</option>'+
                        '    <option value="3" '+ ($("#inputValueField").val()=='3' ? 'selected' :'') +'>{{ trans('global.strong') }}</option>'+
                        '    <option value="4" '+ ($("#inputValueField").val()=='4' ? 'selected' :'') +'>{{ trans('global.very_strong') }}</option>'+
                        '</select>'+
                        '</td>'+
                        '<td><button type="button" class="btn btn-outline-danger remove-input-field">Delete</button></td>'+
                        '</tr>');



                $('#inputTypeField').val(null).trigger('change');
                $('#inputValueField').val(null).trigger('change');
            }
        });

        $(document).on('click', '.remove-input-field', function () {
            $(this).parents('tr').remove();
        });

    });
</script>

@endsection
