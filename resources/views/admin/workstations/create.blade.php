@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.workstation.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.workstations.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.workstation.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.workstation.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="type">{{ trans('cruds.workstation.fields.type') }}</label>
                <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    @if (!$type_list->contains(old('type')))
                        <option> {{ old('type') }}</option>'
                    @endif
                    @foreach($type_list as $t)
                        <option {{ old('type') == $t ? 'selected' : '' }}>{{$t}}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.workstation.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="description">{{ trans('cruds.workstation.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.workstation.fields.description_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="operating_system">{{ trans('cruds.workstation.fields.operating_system') }}</label>
                        <select class="form-control select2-free {{ $errors->has('operating_system') ? 'is-invalid' : '' }}" name="operating_system" id="operating_system">
                            @if (!$operating_system_list->contains(old('operating_system')))
                                <option> {{ old('operating_system') }}</option>'
                            @endif
                            @foreach($operating_system_list as $t)
                                <option {{ old('operating_system') == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('operating_system'))
                        <div class="invalid-feedback">
                            {{ $errors->first('operating_system') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.operating_system_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="address_ip">{{ trans('cruds.workstation.fields.address_ip') }}</label>
                        <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text" name="address_ip" id="address_ip" value="{{ old('address_ip', '') }}">
                        @if($errors->has('address_ip'))
                            <div class="invalid-feedback">
                                {{ $errors->first('address_ip') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.address_ip_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="applications">{{ trans('cruds.workstation.fields.applications') }}</label>
                        <select class="form-control select2 {{ $errors->has('applications') ? 'is-invalid' : '' }}" name="applications[]" id="applications" multiple>
                            @foreach($application_list as $id => $name)
                                <option value="{{ $id }}" {{ in_array($id, old('applications', [])) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('applications'))
                            <div class="invalid-feedback">
                                {{ $errors->first('applications') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.applications_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label for="cpu">{{ trans('cruds.workstation.fields.cpu') }}</label>
                        <select class="form-control select2-free {{ $errors->has('cpu') ? 'is-invalid' : '' }}" name="cpu" id="cpu">
                            @if (!$cpu_list->contains(old('cpu')))
                                <option> {{ old('cpu') }}</option>'
                            @endif
                            @foreach($cpu_list as $t)
                                <option {{ old('cpu') == $t ? 'selected' : '' }}>{{$t}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('cpu'))
                            <div class="invalid-feedback">
                                {{ $errors->first('cpu') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.cpu_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="memory">{{ trans('cruds.workstation.fields.memory') }}</label>
                        <input class="form-control {{ $errors->has('memory') ? 'is-invalid' : '' }}" type="text" name="memory" id="memory" value="{{ old('memory', '') }}">
                        @if($errors->has('memory'))
                            <div class="invalid-feedback">
                                {{ $errors->first('memory') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.memory_helper') }}</span>
                    </div>

                    <div class="form-group">
                        <label for="disk">{{ trans('cruds.workstation.fields.disk') }}</label>
                        <input class="form-control {{ $errors->has('disk') ? 'is-invalid' : '' }}" type="text" name="disk" id="disk" value="{{ old('disk', '') }}">
                        @if($errors->has('disk'))
                            <div class="invalid-feedback">
                                {{ $errors->first('disk') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.workstation.fields.disk_helper') }}</span>
                    </div>
                </div>

            </div>

            <div class="form-group">
                <label for="site_id">{{ trans('cruds.workstation.fields.site') }}</label>
                <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                    @foreach($sites as $id => $site)
                        <option value="{{ $id }}" {{ old('site_id') == $id ? 'selected' : '' }}>{{ $site }}</option>
                    @endforeach
                </select>
                @if($errors->has('site'))
                    <div class="invalid-feedback">
                        {{ $errors->first('site') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.workstation.fields.site_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="building_id">{{ trans('cruds.workstation.fields.building') }}</label>
                <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}" name="building_id" id="building_id">
                    @foreach($buildings as $id => $building)
                        <option value="{{ $id }}" {{ old('building_id') == $id ? 'selected' : '' }}>{{ $building }}</option>
                    @endforeach
                </select>
                @if($errors->has('building'))
                    <div class="invalid-feedback">
                        {{ $errors->first('building') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.workstation.fields.building_helper') }}</span>
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