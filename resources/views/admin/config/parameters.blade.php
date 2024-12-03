@extends('layouts.admin')
@section('content')
<form method="POST" action="{{ route('admin.config.parameters.save') }}" enctype="multipart/form-data">
    @method('PUT')
    @csrf
    <div class="card">
        <div class="card-header">
            {{ trans("cruds.configuration.parameters.title") }}
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="name">{{ trans("cruds.configuration.parameters.help") }}</label>
            </div>

        </div>
        <!---------------------------------------------------------------------------------------------------->
        <div class="card-header">
            {{ trans("cruds.menu.logical_infrastructure.title_short") }}
        </div>
        <!---------------------------------------------------------------------------------------------------->
        <div class="card-body">

            <div class="form-group">
            <label for="name">{{ trans("cruds.configuration.parameters.security_need_auth_helper") }}</label>
        		<div class="form-switch">
                    <input name="security_need_auth" id='security_need_auth' type="checkbox" class="form-check-input"
                        {{ $security_need_auth  ? 'checked' : '' }}>
                    <label for="is_external">{{ trans('cruds.configuration.parameters.security_need_auth') }}</label>
        		</div>
            </div>
        </div>
    </div>

    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.home') }}">
            {{ trans('global.back_to_list') }}
        </a>
        <button class="btn btn-danger" type="submit" name="action" value="save">
            {{ trans('global.save') }}
        </button>
    </div>


</form>

@endsection
