@extends('layouts.admin')

@section('title')
    {{ trans('global.edit') }} {{ $lan->name }}
@endsection

@section('content')
    <form method="POST" action="{{ route("admin.lans.update", [$lan->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.lan.title_singular') }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label class="label-required" for="name">{{ trans('cruds.lan.fields.name') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                                   id="name" value="{{ old('name', $lan->name) }}" required autofocus/>
                            @if($errors->has('name'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('name') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.lan.fields.name_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label for="type">{{ trans('cruds.lan.fields.type') }}</label>
                            <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}"
                                    name="type" id="type">
                                @if (!$type_list->contains(old('type', $lan->type ?? '')))
                                    <option>{{ old('type', $lan->type ?? '') }}</option>
                                @endif
                                @foreach($type_list as $type)
                                    <option {{ old('type', $lan->type ?? '') == $type ? 'selected' : '' }}>{{ $type }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('type'))
                                <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.lan.fields.type_helper') }}</span>
                        </div>
                    </div>
                    <div class="col-sm-5">
                        <div class="form-group">
                            <label for="attributes">{{ trans('cruds.lan.fields.attributes') }}</label>
                            <select class="form-control select2-free-tags {{ $errors->has('attributes') ? 'is-invalid' : '' }}"
                                    name="attributes[]" id="attributes" multiple>
                                @foreach($attributes_list as $a)
                                    <option {{ in_array($a, old('attributes', array_filter(explode(' ', (string) $lan->attributes)))) ? 'selected' : '' }}>{{ $a }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('attributes'))
                                <div class="invalid-feedback">{{ $errors->first('attributes') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.lan.fields.attributes_helper') }}</span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1" for="description">{{ trans('cruds.lan.fields.description') }}</label>
                    <input class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" type="text"
                           name="description" id="description" value="{{ old('description', $lan->description) }}">
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.lan.fields.description_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.lans.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-success" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
