@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.mans.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.man.title_singular') }}
        </div>

        <div class="card-body">

            <div class="form-group">
                <label class="label-required" for="name">{{ trans('cruds.man.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required autofocus/>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.man.fields.name_helper') }}</span>
            </div>

                <div class="row">
                    <div class="col-8">
                        <div class="form-group">
                            <label class="label-maturity-1"
                                   for="description">{{ trans('cruds.wan.fields.description') }}</label>
                            <textarea
                                    class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                                    name="description"
                                    id="description">{!! old('description', '') !!}</textarea>
                            @if($errors->has('description'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('description') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.wan.fields.description_helper') }}</span>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-4">
                        <div class="form-group">
                            <label for="wans">{{ trans('cruds.man.fields.wans') }}</label>
                            <select class="form-control select2 {{ $errors->has('wans') ? 'is-invalid' : '' }}"
                                    name="wans[]"
                                    id="wans[]" multiple>
                                <option value="">{{ trans('global.pleaseSelect') }}</option>
                                @foreach($wans as $id => $name)
                                    <option value="{{ $id }}" {{ in_array($id, old('wans', [])) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('wans'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('wans') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.man.fields.wans_helper') }}</span>
                        </div>

                    </div>
                    <div class="col-4">
                        <div class="form-group">
                            <label for="parent_man_id">{{ trans('cruds.man.fields.parent_man') }}</label>
                            <select class="form-control select2 {{ $errors->has('parent_man_id') ? 'is-invalid' : '' }}" name="parent_man_id" id="parent_man_id">
                            <option value=""></option>
                            @foreach($mans as $id => $name)
                                <option value="{{ $id }}" {{ in_array($id, old('mans', [])) ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-8">

                        <div class="form-group">
                            <label for="lans">{{ trans('cruds.man.fields.lans') }}</label>
                            <div style="padding-bottom: 4px">
                                <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                            </div>
                            <select class="form-control select2 {{ $errors->has('lans') ? 'is-invalid' : '' }}" name="lans[]" id="lans" multiple>
                                @foreach($lans as $id => $lans)
                                    <option value="{{ $id }}" {{ in_array($id, old('lans', [])) ? 'selected' : '' }}>{{ $lans }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('lans'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('lans') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.man.fields.lans_helper') }}</span>
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.mans.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button id="btn-save" class="btn btn-success" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>


@endsection
