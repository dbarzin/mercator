@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route("admin.logical-flows.store") }}" enctype="multipart/form-data">
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans('global.edit') }} {{ trans('cruds.logicalFlow.title_singular') }}
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label class="recommended" for="name">{{ trans('cruds.logicalFlow.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name') }}">
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.name_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        <label class="recommended" for="description">{{ trans('cruds.logicalFlow.fields.description') }}</label>
                        <textarea class="form-control ckeditor {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description">{!! old('description') !!}</textarea>
                        @if($errors->has('description'))
                            <div class="invalid-feedback">
                                {{ $errors->first('description') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.description_helper') }}</span>
                    </div>
                </div>
            </div>

            <!------------------------------------------------------------------------------------------------------>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label class="recommended" for="router">{{ trans('cruds.logicalFlow.fields.router') }}</label>
                        <select class="form-control select2 {{ $errors->has('router') ? 'is-invalid' : '' }}" name="router_id" id="router_id">
                            <option></option>
                            @foreach($routers as $id => $name)
                            <option value="{{$id}}" {{ old('router_id') === $id ? 'selected' : '' }}>{{$name}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('router'))
                        <div class="invalid-feedback">
                            {{ $errors->first('router') }}
                        </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.router_helper') }}</span>
                    </div>
                </div>
            </div>
            <!------------------------------------------------------------------------------------------------------>
            <div class="row">
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="recommended" for="name">{{ trans('cruds.logicalFlow.fields.priority') }}</label>
                        <input class="form-control {{ $errors->has('priority') ? 'is-invalid' : '' }}" type="text" name="priority" id="priority" value="{{ old('priority') }}" required>
                        @if($errors->has('priority'))
                            <div class="invalid-feedback">
                                {{ $errors->first('priority') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.priority_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="recommended" for="name">{{ trans('cruds.logicalFlow.fields.action') }}</label>

                        <select class="form-control select2 {{ $errors->has('action') ? 'is-invalid' : '' }}" name="action" id="action">
                            <option></option>
                            <option {{ old('action')=="Permit" ? 'selected' : '' }}>Permit</option>
                            <option {{ old('action')=="Deny" ? 'selected' : '' }}>Deny</option>
                            <option {{ old('action')=="Block" ? 'selected' : '' }}>Block</option>
                            <option {{ old('action')=="Reditect" ? 'selected' : '' }}>Reditect</option>
                            <option {{ old('action')=="Log" ? 'selected' : '' }}>Log</option>
                        </select>

                        @if($errors->has('action'))
                            <div class="invalid-feedback">
                                {{ $errors->first('action') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.action_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm-1">
                    <div class="form-group">
                        <label class="recommended" for="name">{{ trans('cruds.logicalFlow.fields.protocol') }}</label>
                        <select class="form-control select2-free {{ $errors->has('protocol') ? 'is-invalid' : '' }}" name="protocol" id="protocol">
                            @if (!$protocol_list->contains(old('protocol')))
                            <option> {{ old('protocol') }}</option>'
                            @endif
                            @foreach($protocol_list as $protocol)
                            <option {{ old('protocol') == $protocol ? 'selected' : '' }}>{{$protocol}}</option>
                            @endforeach
                        </select>
                        @if($errors->has('protocol'))
                            <div class="invalid-feedback">
                                {{ $errors->first('protocol') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.protocol_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.logicalFlow.fields.source_ip_range') }}</label>
                        <input class="form-control {{ $errors->has('protocol') ? 'is-invalid' : '' }}" type="text" name="source_ip_range" id="source_ip_range" value="{{ old('source_ip_range') }}" required>
                        @if($errors->has('source_ip_range'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source_ip_range') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.source_ip_range_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.logicalFlow.fields.source_port') }}</label>
                        <input class="form-control {{ $errors->has('protocol') ? 'is-invalid' : '' }}" type="text" name="source_port" id="source_port" value="{{ old('source_port') }}">
                        @if($errors->has('source_port'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source_port') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.source_port_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-3">
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.logicalFlow.fields.dest_ip_range') }}</label>
                        <input class="form-control {{ $errors->has('source_ip_range') ? 'is-invalid' : '' }}" type="text" name="dest_ip_range" id="dest_ip_range" value="{{ old('dest_ip_range') }}" required>
                        @if($errors->has('dest_ip_range'))
                            <div class="invalid-feedback">
                                {{ $errors->first('dest_ip_range') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.dest_ip_range_helper') }}</span>
                    </div>
                </div>

                <div class="col-sm-1">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.logicalFlow.fields.dest_port') }}</label>
                        <input class="form-control {{ $errors->has('protocol') ? 'is-invalid' : '' }}" type="text" name="dest_port" id="dest_port" value="{{ old('dest_port') }}">
                        @if($errors->has('dest_port'))
                            <div class="invalid-feedback">
                                {{ $errors->first('source_port') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.dest_port_helper') }}</span>
                    </div>
                </div>


            </div>

            <!------------------------------------------------------------------------------------------------------>
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.logicalFlow.fields.users') }}</label>
                        <input class="form-control {{ $errors->has('users') ? 'is-invalid' : '' }}" type="text" name="users" id="users" value="{{ old('users') }}">
                        @if($errors->has('users'))
                            <div class="invalid-feedback">
                                {{ $errors->first('users') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.users_helper') }}</span>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label for="name">{{ trans('cruds.logicalFlow.fields.schedule') }}</label>
                        <input class="form-control {{ $errors->has('schedule') ? 'is-invalid' : '' }}" type="text" name="schedule" id="schedule" value="{{ old('schedule') }}">
                        @if($errors->has('schedule'))
                            <div class="invalid-feedback">
                                {{ $errors->first('schedule') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.logicalFlow.fields.schedule_helper') }}</span>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.logical-flows.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button class="btn btn-danger" type="submit">
            {{ trans('global.save') }}
        </button>
    </div>
</form>
@endsection
