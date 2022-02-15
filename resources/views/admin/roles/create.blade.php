@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.role.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.roles.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.role.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">
                        {{ $errors->first('title') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.role.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required mb-4" for="permissions">{{ trans('cruds.role.fields.permissions') }}</label>
                <div class="row">

                @foreach($permissions_sorted as $permissions)
                    <div class="col-md-4 mb-4">
                        <h2>{{ $permissions['name'] }}</h2>
                        @foreach($permissions['actions'] as $action)
                            <div class="form-check form-switch form-switch-lg">
                                <input class="form-check-input" name="permissions[]" type="checkbox" value="{{ $action[0] }}" id="flexSwitchCheckChecked" {{ (in_array($action[0], old('permissions', []))) ? 'checked' : ''}} >
                                <label class="form-check-label" for="flexSwitchCheckChecked">{{ $action[1] }}</label>
                            </div>
                        @endforeach
                    </div>
                @endforeach
                </div>
                @if($errors->has('permissions'))
                    <div class="invalid-feedback">
                        {{ $errors->first('permissions') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.role.fields.permissions_helper') }}</span>
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
