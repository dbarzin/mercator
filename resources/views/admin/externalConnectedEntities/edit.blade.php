@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.externalConnectedEntity.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.external-connected-entities.update", [$externalConnectedEntity->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.externalConnectedEntity.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $externalConnectedEntity->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="responsible_sec">{{ trans('cruds.externalConnectedEntity.fields.responsible_sec') }}</label>
                <input class="form-control {{ $errors->has('responsible_sec') ? 'is-invalid' : '' }}" type="text" name="responsible_sec" id="responsible_sec" value="{{ old('responsible_sec', $externalConnectedEntity->responsible_sec) }}">
                @if($errors->has('responsible_sec'))
                    <div class="invalid-feedback">
                        {{ $errors->first('responsible_sec') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.responsible_sec_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="contacts">{{ trans('cruds.externalConnectedEntity.fields.contacts') }}</label>
                <input class="form-control {{ $errors->has('contacts') ? 'is-invalid' : '' }}" type="text" name="contacts" id="contacts" value="{{ old('contacts', $externalConnectedEntity->contacts) }}">
                @if($errors->has('contacts'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contacts') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.contacts_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="connected_networks">{{ trans('cruds.externalConnectedEntity.fields.connected_networks') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('connected_networks') ? 'is-invalid' : '' }}" name="connected_networks[]" id="connected_networks" multiple>
                    @foreach($connected_networks as $id => $connected_networks)
                        <option value="{{ $id }}" {{ (in_array($id, old('connected_networks', [])) || $externalConnectedEntity->connected_networks->contains($id)) ? 'selected' : '' }}>{{ $connected_networks }}</option>
                    @endforeach
                </select>
                @if($errors->has('connected_networks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('connected_networks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.externalConnectedEntity.fields.connected_networks_helper') }}</span>
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