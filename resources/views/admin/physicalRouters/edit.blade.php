@extends('layouts.admin')
@section('content')

<form method="POST" action="{{ route("admin.physical-routers.update", [$physicalRouter->id]) }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.physicalRouter.title_singular') }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="type">{{ trans('cruds.physicalRouter.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('type', $physicalRouter->name) }}" required autofocus/>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.physicalRouter.fields.name_helper') }}</span>
                    </div>
                </div>

                <div class="col-md-4">

                <div class="form-group">
                    <label class="recommended" for="type">{{ trans('cruds.physicalRouter.fields.type') }}</label>
                    <select class="form-control select2-free {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                        @if (!$type_list->contains(old('type')))
                            <option> {{ old('type') }}</option>
                        @endif
                        @foreach($type_list as $t)
                            <option {{ (old('type') ? old('type') : $physicalRouter->type) == $t ? 'selected' : '' }}>{{$t}}</option>
                        @endforeach
                    </select>
                    @if($errors->has('type'))
                        <div class="invalid-feedback">
                            {{ $errors->first('type') }}
                        </div>
                    @endif
                    <span class="help-block">{{ trans('cruds.physicalRouter.fields.type_helper') }}</span>
                </div>


                </div>
            </div>

            <div class="form-group">
                <label for="description">{{ trans('cruds.physicalRouter.fields.description') }}</label>
                <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description', $physicalRouter->description) !!}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">
                        {{ $errors->first('description') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalRouter.fields.description_helper') }}</span>
            </div>


            <div class="row">
                <div class="col-md-4">

                    <div class="form-group">
                        <label for="site_id">{{ trans('cruds.physicalRouter.fields.site') }}</label>
                        <select class="form-control select2 {{ $errors->has('site') ? 'is-invalid' : '' }}" name="site_id" id="site_id">
                            <option value="">{{ trans('global.pleaseSelect') }} </option>
                            @foreach($sites as $id => $site)
                                <option value="{{ $id }}" {{ ($physicalRouter->site ? $physicalRouter->site->id : old('site_id')) == $id ? 'selected' : '' }}>{{ $site }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('site'))
                            <div class="invalid-feedback">
                                {{ $errors->first('site') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.physicalRouter.fields.site_helper') }}</span>
                    </div>

                </div>
                <div class="col-md-4">


                    <div class="form-group">
                        <label for="building_id">{{ trans('cruds.physicalRouter.fields.building') }}</label>
                        <select class="form-control select2 {{ $errors->has('building') ? 'is-invalid' : '' }}" name="building_id" id="building_id">
                            <option value="">{{ trans('global.pleaseSelect') }} </option>
                            @foreach($buildings as $id => $building)
                                <option value="{{ $id }}" {{ ($physicalRouter->building ? $physicalRouter->building->id : old('building_id')) == $id ? 'selected' : '' }}>{{ $building }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('building'))
                            <div class="invalid-feedback">
                                {{ $errors->first('building') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.physicalRouter.fields.building_helper') }}</span>
                    </div>

                </div>
                <div class="col-md-4">

                    <div class="form-group">
                        <label for="bay_id">{{ trans('cruds.physicalRouter.fields.bay') }}</label>
                        <select class="form-control select2 {{ $errors->has('bay') ? 'is-invalid' : '' }}" name="bay_id" id="bay_id">
                            <option value="">{{ trans('global.pleaseSelect') }} </option>
                            @foreach($bays as $id => $bay)
                                <option value="{{ $id }}" {{ ($physicalRouter->bay ? $physicalRouter->bay->id : old('bay_id')) == $id ? 'selected' : '' }}>{{ $bay }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('bay'))
                            <div class="invalid-feedback">
                                {{ $errors->first('bay') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.physicalRouter.fields.bay_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="vlans">{{ trans('cruds.physicalRouter.fields.routers') }}</label>
                <select class="form-control select2 {{ $errors->has('routers') ? 'is-invalid' : '' }}" name="routers[]" id="routers" multiple>
                    @foreach($routers as $id => $router)
                        <option value="{{ $id }}" {{ (in_array($id, old('routers', [])) || $physicalRouter->routers->contains($id)) ? 'selected' : '' }}>{{ $router }}</option>
                    @endforeach
                </select>
                @if($errors->has('routers'))
                    <div class="invalid-feedback">
                        {{ $errors->first('routers') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalRouter.fields.vlan_helper') }}</span>
            </div>

            <div class="form-group">
                <label for="vlans">{{ trans('cruds.physicalRouter.fields.vlan') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('vlans') ? 'is-invalid' : '' }}" name="vlans[]" id="vlans" multiple>
                    @foreach($vlans as $id => $vlan)
                        <option value="{{ $id }}" {{ (in_array($id, old('vlans', [])) || $physicalRouter->vlans->contains($id)) ? 'selected' : '' }}>{{ $vlan }}</option>
                    @endforeach
                </select>
                @if($errors->has('vlans'))
                    <div class="invalid-feedback">
                        {{ $errors->first('vlans') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.physicalRouter.fields.vlan_helper') }}</span>
            </div>
        </div>
    </div>
    <div class="form-group">
        <a id="btn-cancel" id="btn-cancel" class="btn btn-default" href="{{ route('admin.physical-routers.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button id="btn-save" id="btn-save" class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection
