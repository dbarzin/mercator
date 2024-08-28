@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.wifi-terminals.update", [$wifiTerminal->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.wifiTerminal.title_singular') }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.wifiTerminal.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $wifiTerminal->name) }}" required>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.wifiTerminal.fields.name_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="type">{{ trans('cruds.wifiTerminal.fields.type') }}</label>
                        <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                            @foreach($type_list as $type)
                            <option {{ $wifiTerminal->type==$type ? 'selected' : '' }}>{{$type}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('type'))
                            <div class="invalid-feedback">
                                {{ $errors->first('type') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.wifiTerminal.fields.type_helper') }}</span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <label for="description">{{ trans('cruds.wifiTerminal.fields.description') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $wifiTerminal->description) !!}</textarea>
                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.wifiTerminal.fields.description_helper') }}</span>
                    </div>
                </div>
            </div>
        </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.logical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="col-sm-6">
            <div class="form-group">
                <label for="address_ip">{{ trans('cruds.wifiTerminal.fields.address_ip') }}</label>
                <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text" name="address_ip" id="address_ip" value="{{ old('address_ip', $wifiTerminal->address_ip) }}">
                @if($errors->has('address_ip'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address_ip') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.wifiTerminal.fields.address_ip_helper') }}</span>
            </div>
        </div>
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-header">
        {{ trans("cruds.menu.physical_infrastructure.title_short") }}
    </div>
    <!---------------------------------------------------------------------------------------------------->
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="form-group">
                    <label for="site_id">{{ trans('cruds.wifiTerminal.fields.site') }}</label>
                    <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                        @foreach($sites as $id => $site)
                            <option value="{{ $id }}" {{ ($wifiTerminal->site ? $wifiTerminal->site->id : old('site_id')) == $id ? 'selected' : '' }}>{{ $site }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('site'))
                        <div class="invalid-feedback">
                            {{ $errors->first('site') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.wifiTerminal.fields.site_helper') }}</span>
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="building_id">{{ trans('cruds.wifiTerminal.fields.building') }}</label>
                    <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}" name="building_id" id="building_id">
                        @foreach($buildings as $id => $building)
                            <option value="{{ $id }}" {{ ($wifiTerminal->building ? $wifiTerminal->building->id : old('building_id')) == $id ? 'selected' : '' }}>{{ $building }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('building'))
                        <div class="invalid-feedback">
                            {{ $errors->first('building') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.wifiTerminal.fields.building_helper') }}</span>
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
    })

});
</script>
@endsection
