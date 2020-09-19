@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.vlan.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.vlans.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.vlan.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.vlan.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.description_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.vlan.fields.address') }}</label>
                <input class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" type="text" name="address" id="address" value="{{ old('address', '') }}">
                @if($errors->has('address'))
                    <div class="invalid-feedback">
                        {{ $errors->first('address') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="mask">{{ trans('cruds.vlan.fields.mask') }}</label>
                <input class="form-control {{ $errors->has('mask') ? 'is-invalid' : '' }}" type="text" name="mask" id="mask" value="{{ old('mask', '') }}">
                @if($errors->has('mask'))
                    <div class="invalid-feedback">
                        {{ $errors->first('mask') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.mask_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="gateway">{{ trans('cruds.vlan.fields.gateway') }}</label>
                <input class="form-control {{ $errors->has('gateway') ? 'is-invalid' : '' }}" type="text" name="gateway" id="gateway" value="{{ old('gateway', '') }}">
                @if($errors->has('gateway'))
                    <div class="invalid-feedback">
                        {{ $errors->first('gateway') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.gateway_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="zone">{{ trans('cruds.vlan.fields.zone') }}</label>
                <input class="form-control {{ $errors->has('zone') ? 'is-invalid' : '' }}" type="text" name="zone" id="zone" value="{{ old('zone', '') }}">
                @if($errors->has('zone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('zone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.zone_helper') }}</span>
            </div>


            <div class="form-group">
                <label for="vlans">{{ trans('cruds.vlan.fields.routers') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('physicalRouters') ? 'is-invalid' : '' }}" name="physicalRouters[]" id="physicalRouters" multiple>
                    @foreach($physicalRouters as $id => $physicalRouter)
                        <option value="{{ $id }}" {{ in_array($id, old('physicalRouters', [])) ? 'selected' : '' }}>{{ $physicalRouter }}</option>
                    @endforeach
                </select>
                @if($errors->has('physicalRouters'))
                    <div class="invalid-feedback">
                        {{ $errors->first('physicalRouters') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.vlan.fields.routers_helper') }}</span>
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