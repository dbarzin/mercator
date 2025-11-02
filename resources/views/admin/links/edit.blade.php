@extends('layouts.admin')
@section('content')
    <form method="POST" action="{{ route('admin.links.update', [$link->id]) }}" enctype="multipart/form-data">
        @method('PUT')
        @csrf
        <div class="card">
            <div class="card-header">
                {{ trans('global.edit') }} {{ trans('cruds.physicalLink.title_singular') }}
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.physicalLink.fields.src') }}</label>
                            <select class="form-control select2 {{ $errors->has('src_id') ? 'is-invalid' : '' }}"
                                    name="src_id" id="src_id" autofocus>
                                <option>...</option>
                                @foreach($devices as $id => $name)
                                    <option value="{{ $id }}" {{ (old('src_id') ? old('src_id') : $link->sourceId()) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('src_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('src_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.physicalLink.fields.src_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.physicalLink.fields.src_port') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="src_port" id="src_port" value="{{ old('src_port', $link->src_port) }}">
                            @if($errors->has('src_port'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('src_port') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.physicalLink.fields.src_port_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-sm-3">
                        <div class="form-group">
                            <label class="required" for="name">{{ trans('cruds.physicalLink.fields.dest') }}</label>
                            <select class="form-control select2 {{ $errors->has('dest_id') ? 'is-invalid' : '' }}"
                                    name="dest_id" id="dest_id">
                                <option>...</option>
                                @foreach($devices as $id => $name)
                                    <option value="{{ $id }}" {{ (old('dest_id') ? old('dest_id') : $link->destinationId()) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('dest_id'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dest_id') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.physicalLink.fields.dest_helper') }}</span>
                        </div>
                    </div>

                    <div class="col-sm-1">
                        <div class="form-group">
                            <label class="required"
                                   for="name">{{ trans('cruds.physicalLink.fields.dest_port') }}</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text"
                                   name="dest_port" id="dest_port" value="{{ old('dest_port', $link->dest_port) }}">
                            @if($errors->has('dest_port'))
                                <div class="invalid-feedback">
                                    {{ $errors->first('dest_port') }}
                                </div>
                            @endif
                            <span class="help-block">{{ trans('cruds.physicalLink.fields.dest_port_helper') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group">
            <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.links.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <button id="btn-save" class="btn btn-danger" type="submit">
                {{ trans('global.save') }}
            </button>
        </div>
    </form>
@endsection
