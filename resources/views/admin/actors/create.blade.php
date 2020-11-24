@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.actor.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.actors.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.actor.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actor.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="contact">{{ trans('cruds.actor.fields.contact') }}</label>
                <input class="form-control {{ $errors->has('contact') ? 'is-invalid' : '' }}" type="text" name="contact" id="contact" value="{{ old('contact', '') }}">
                @if($errors->has('contact'))
                    <div class="invalid-feedback">
                        {{ $errors->first('contact') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actor.fields.contact_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="recommended" for="nature">{{ trans('cruds.actor.fields.nature') }}</label>
                <input class="form-control {{ $errors->has('nature') ? 'is-invalid' : '' }}" type="text" name="nature" id="nature" value="{{ old('nature', '') }}">
                @if($errors->has('nature'))
                    <div class="invalid-feedback">
                        {{ $errors->first('nature') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actor.fields.nature_helper') }}</span>
            </div>
            <div class="form-group">
                <label fclass="recommended" or="type">{{ trans('cruds.actor.fields.type') }}</label>
                <input class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" type="text" name="type" id="type" value="{{ old('type', '') }}">
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.actor.fields.type_helper') }}</span>
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