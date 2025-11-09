@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route("admin.forest-ads.update", [$forestAd->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf

        <div class="card">
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.forestAd.title_singular') }}
            </div>

            <div class="card-body">
                <div class="form-group">
                    <label class="label-required" for="name">{{ trans('cruds.forestAd.fields.name') }}</label>
                    <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name"
                           id="name" value="{{ old('name', $forestAd->name) }}" required autofocus/>
                    @if($errors->has('name'))
                        <div class="invalid-feedback">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.forestAd.fields.name_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1"
                           for="description">{{ trans('cruds.forestAd.fields.description') }}</label>
                    <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}"
                              name="description"
                              id="description">{!! old('description', $forestAd->description) !!}</textarea>
                    @if($errors->has('description'))
                        <div class="invalid-feedback">
                            {{ $errors->first('description') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.forestAd.fields.description_helper') }}</span>
                </div>
                <div class="form-group">
                    <label class="label-maturity-1"
                           for="zone_admin_id">{{ trans('cruds.forestAd.fields.zone_admin') }}</label>
                    <select class="form-control select2 {{ $errors->has('zone_admin') ? 'is-invalid' : '' }}"
                            name="zone_admin_id" id="zone_admin_id">
                        @foreach($zone_admins as $id => $zone_admin)
                            <option value="{{ $id }}" {{ ($forestAd->zone_admin ? $forestAd->zone_admin->id : old('zone_admin_id')) == $id ? 'selected' : '' }}>{{ $zone_admin }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('zone_admin'))
                        <div class="invalid-feedback">
                            {{ $errors->first('zone_admin') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.forestAd.fields.zone_admin_helper') }}</span>
                </div>
                <div class="form-group">
                    <label for="domaines">{{ trans('cruds.forestAd.fields.domaines') }}</label>
                    <div style="padding-bottom: 4px">
                        <span class="btn btn-info btn-xs select-all"
                              style="border-radius: 0">{{ trans('global.select_all') }}</span>
                        <span class="btn btn-info btn-xs deselect-all"
                              style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                    </div>
                    <select class="form-control select2 {{ $errors->has('domaines') ? 'is-invalid' : '' }}"
                            name="domaines[]" id="domaines" multiple>
                        @foreach($domaines as $id => $domaines)
                            <option value="{{ $id }}" {{ (in_array($id, old('domaines', [])) || $forestAd->domaines->contains($id)) ? 'selected' : '' }}>{{ $domaines }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('domaines'))
                        <div class="invalid-feedback">
                            {{ $errors->first('domaines') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.forestAd.fields.domaines_helper') }}</span>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.forest-ads.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
