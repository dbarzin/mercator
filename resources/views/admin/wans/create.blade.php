@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.wans.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.create') }} {{ trans('cruds.wan.title_singular') }}
        </div>

        <div class="card-body">
            <div class="form-group">
                <label for="name">{{ trans('cruds.wan.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.wan.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mans">{{ trans('cruds.wan.fields.mans') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('mans') ? 'is-invalid' : '' }}" name="mans[]" id="mans" multiple>
                    @foreach($mans as $id => $mans)
                        <option value="{{ $id }}" {{ in_array($id, old('mans', [])) ? 'selected' : '' }}>{{ $mans }}</option>
                    @endforeach
                </select>
                @if($errors->has('mans'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mans') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.wan.fields.mans_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="lans">{{ trans('cruds.wan.fields.lans') }}</label>
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
                <span class="help-block">{{ trans('cruds.wan.fields.lans_helper') }}</span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.wans.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection
