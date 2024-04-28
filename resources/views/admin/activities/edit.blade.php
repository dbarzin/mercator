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
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.activity.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $activity->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.activity.fields.name_helper') }}</span>
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
                                <option value="{{ $id }}" {{ (in_array($id, old('processes', [])) || $activity->activitiesProcesses->contains($id)) ? 'selected' : '' }}>{{ $name }}</option>
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
        }
    );

    });
</script>
@endsection
