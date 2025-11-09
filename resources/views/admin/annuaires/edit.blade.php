@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.annuaires.update", [$annuaire->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.annuaire.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="label-required" for="name">{{ trans('cruds.annuaire.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', $annuaire->name) }}" required autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.annuaire.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1"
                           for="description">{{ trans('cruds.annuaire.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description"
                              id="description">{!! old('description', $annuaire->description) !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.annuaire.fields.description_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1" for="solution">{{ trans('cruds.annuaire.fields.solution') }}</label>
                    <input class="form-control {{ $errors->has('solution') ? 'is-invalid' : '' }}" type="text"
                           name="solution" id="solution" value="{{ old('solution', $annuaire->solution) }}">
                    @if($errors->has('solution'))
                        <div class="invalid-feedback">
                            {{ $errors->first('solution') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.annuaire.fields.solution_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1"
                           for="zone_admin_id">{{ trans('cruds.annuaire.fields.zone_admin') }}</label>
                    <select class="form-control select2 {{ $errors->has('zone_admin') ? 'is-invalid' : '' }}"
                            name="zone_admin_id" id="zone_admin_id">
                        @foreach($zone_admins as $id => $zone_admin)
                            <option value="{{ $id }}" {{ ($annuaire->zone_admin ? $annuaire->zone_admin->id : old('zone_admin_id')) == $id ? 'selected' : '' }}>{{ $zone_admin }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('zone_admin'))
                        <div class="invalid-feedback">
                            {{ $errors->first('zone_admin') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.annuaire.fields.zone_admin_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.annuaires.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
