@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.vlan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.vlans.update", [$vlan->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.vlan.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $vlan->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.vlan.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $vlan->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.description_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="subnetworks">{{ trans('cruds.vlan.fields.subnetworks') }}</label>

                <select class="form-control select2 {{ $errors->has('subnetworks') ? 'is-invalid' : '' }}" name="subnetworks[]" id="subnetworks" multiple>
                    @foreach($subnetworks as $id => $subnetwork)
                        <option value="{{ $id }}" {{ (in_array($id, old('subnetworks', [])) || $vlan->subnetworks->contains($id)) ? 'selected' : '' }}>{{ $subnetwork }}</option>
                    @endforeach
                </select>
                @if($errors->has('subnetworks'))
                    <div class="invalid-feedback">
                        {{ $errors->first('subnetworks') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.subnetworks_helper') }}</span>
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