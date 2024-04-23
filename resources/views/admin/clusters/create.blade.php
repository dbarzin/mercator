@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.clusters.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.cluster.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.cluster.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cluster.fields.name_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="type">{{ trans('cruds.cluster.fields.type') }}</label>
                <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    <option></option>
                    @foreach($type_list as $t)
                        <option {{ old('type') == $t ? 'selected' : '' }}>{{$t}}</option>
                    @endforeach
                    @if (!$type_list->contains(old('type')))
                        <option {{ old('type') ? 'selected' : ''}}> {{ old('type') }}</option>
                    @endif
                </select>

                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cluster.fields.type_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.cluster.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cluster.fields.description_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.cluster.fields.address_ip') }}</label>
                <input class="form-control {{ $errors->has('address_ip') ? 'is-invalid' : '' }}" type="text" name="address_ip" id="address_ip" value="{{ old('address_ip', '') }}">
                @if($errors->has('address_ip'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address_ip') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cluster.fields.address_ip_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="logical_servers">{{ trans('cruds.cluster.fields.logical_servers') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('logical_servers') ? 'is-invalid' : '' }}" name="logical_servers[]" id="logical_servers" multiple>
                    @foreach($logical_servers as $id => $logical_servers)
                        <option value="{{ $id }}" {{ in_array($id, old('logical_servers', [])) ? 'selected' : '' }}>{{ $logical_servers }}</option>
                    @endforeach
                </select>
                @if($errors->has('logical_servers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('logical_servers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cluster.fields.logical_servers_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="logical_servers">{{ trans('cruds.cluster.fields.physical_servers') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('logical_servers') ? 'is-invalid' : '' }}" name="logical_servers[]" id="logical_servers" multiple>
                    @foreach($physical_servers as $id => $physical_servers)
                        <option value="{{ $id }}" {{ in_array($id, old('physical_servers', [])) ? 'selected' : '' }}>{{ $physical_servers }}</option>
                    @endforeach
                </select>
                @if($errors->has('physical_servers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('physical_servers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.cluster.fields.physical_servers_helper') }}</span>
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
