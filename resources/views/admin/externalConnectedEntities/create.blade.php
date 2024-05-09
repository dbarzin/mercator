@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route('admin.external-connected-entities.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.externalConnectedEntity.title_singular') }}
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.externalConnectedEntity.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name','') }}" required>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.name_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="type">{{ trans('cruds.externalConnectedEntity.fields.type') }}</label>
                        <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                            @if (!$type_list->contains(old('type')))
                                <option> {{ old('type','') }}</option>'
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
                        <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.type_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label class="recommended" for="entity_resp_id">{{ trans('cruds.externalConnectedEntity.fields.entity') }}</label>
                        <select class="form-control select2 {{ $errors->has('entity') ? 'is-invalid' : '' }}" name="entity_id" id="entity_id">
                            <option></option>
                            @foreach($entities as $id => $entity)
                                <option value="{{ $id }}" {{  old('entity_id','') == $id ? 'selected' : '' }}>{{ $entity }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('entity'))
                            <div class="invalid-feedback">
                                {{ $errors->first('entity') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.entity_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label for="contacts">{{ trans('cruds.externalConnectedEntity.fields.contacts') }}</label>
                        <input class="form-control {{ $errors->has('contacts') ? 'is-invalid' : '' }}" type="text" name="contacts" id="contacts" value="{{ old('contacts', '') }}">
                        @if($errors->has('contacts'))
                            <div class="invalid-feedback">
                                {{ $errors->first('contacts') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.contacts_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="recommended" for="description">{{ trans('cruds.externalConnectedEntity.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.description_helper') }}</span>
            </div>

            <label class="recommended" for="network_id">{{ trans('cruds.externalConnectedEntity.fields.network') }}</label>
            <select class="form-control select2 {{ $errors->has('network') ? 'is-invalid' : '' }}" name="network_id" id="network_id">
                <option></option>
                @foreach($networks as $id => $network)
                    <option value="{{ $id }}" {{ old('network_id') == $id ? 'selected' : '' }}>{{ $network }}</option>
                @endforeach
            </select>
            @if($errors->has('network'))
                <div class="invalid-feedback">
                    {{ $errors->first('network') }}
                </div>
            @endif
            <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.network_helper') }}</span>

            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <label for="contacts">{{ trans('cruds.externalConnectedEntity.fields.src') }}</label>
                        <input class="form-control {{ $errors->has('src') ? 'is-invalid' : '' }}" type="text" name="src" id="src" value="{{ old('src', '') }}">
                        @if($errors->has('src'))
                            <div class="invalid-feedback">
                                {{ $errors->first('src') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.src_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm">
                    <div class="form-group">
                        <label for="contacts">{{ trans('cruds.externalConnectedEntity.fields.dest') }}</label>
                        <input class="form-control {{ $errors->has('dest') ? 'is-invalid' : '' }}" type="text" name="dest" id="dest" value="{{ old('src', '') }}">
                        @if($errors->has('dest'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dest') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.dest_helper') }}</span>
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
  $(".select2-free").select2({
        placeholder: "{{ trans('global.pleaseSelect') }}",
        allowClear: true,
        tags: true
    });
});
</script>
@endsection
