@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('panel.menu.options') }}
    </div>

    <div class="card-body">
        <form method="POST" action="/profile/preferences">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans("cruds.user.fields.language") }}</label>
                <select class="form-control select2 {{ $errors->has('language') ? 'is-invalid' : '' }}" name="language" id="language">
                    <option value="en" {{ auth()->user()->language == 'en' ? 'selected' : '' }}>{{ trans("cruds.user.fields.language_en") }}</option>
                    <option value="fr" {{ auth()->user()->language == 'fr' ? 'selected' : '' }}>{{ trans("cruds.user.fields.language_fr") }}</option>
                    <!-- <option value="de" {{ auth()->user()->language == 'de' ? 'selected' : '' }}>{{ trans("cruds.user.fields.language_de") }}</option> -->
                </select>
                @if($errors->has('language'))
                    <div class="invalid-feedback">
                        {{ $errors->first('language') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.language_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="title">{{ trans("cruds.user.fields.granularity") }}</label>
                <select class="form-control select2 {{ $errors->has('granularity') ? 'is-invalid' : '' }}" name="granularity" id="granularity">
                    <option value="1" {{ auth()->user()->granularity == 1 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_1") }}</option>
                    <option value="2" {{ auth()->user()->granularity == 2 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_2") }}</option>
                    <option value="3" {{ auth()->user()->granularity == 3 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_3") }}</option>
                </select>
                @if($errors->has('granularity'))
                    <div class="invalid-feedback">
                        {{ $errors->first('granularity') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.granularity_helper') }}</span>
            </div>

            <div class="form-group">
                <button id="btn-save" class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
