@extends('layouts.app')
@section('styles')
<style>
    body {
        background-image: url('/images/mercator.png');
        background-size:  800px 800px;
        background-position: center;
        background-repeat: no-repeat;
        background-color: #FFFFFF;
        height: 800px;
        }

    .card {
        background-color: rgba(255, 255, 255, 0.6);
        border-radius: 10px;
        padding: 2px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .text-muted {
        color: black !important;
    }

    .input-group-text {
        background-color: rgba(255, 255, 255);
    }

    .form-control {
        background-color: rgba(255, 255, 250, 0.7);
    }
</style>
@endsection
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mx-4">
            <div class="card-body p-4">

                <p class="text-muted">{{ (env('APP_NAME') === null) || (env('APP_NAME') === "Laravel") ? "Mercator" : env('APP_NAME') }} :: Login</p>

                @if(session('message'))
                    <div class="alert alert-info" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text">
                                <i class="bi bi-person-fill"></i>
                            </span>
                        </div>

                        <input id="email" name="email" type="text" class="form-control {{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_id') }}" value="{{ old('email', null) }}">

                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="bi bi-lock-fill"></i></i></span>
                        </div>

                        <input id="password" name="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" required placeholder="{{ trans('global.login_password') }}">

                        @if($errors->has('password'))
                            <div class="invalid-feedback">
                                {{ $errors->first('password') }}
                            </div>
                        @endif
                    </div>

                    <div class="input-group mb-4">
                        <div class="form-check checkbox">
                            <input class="form-check-input" name="remember" type="checkbox" id="remember" style="vertical-align: middle;" />
                            <label class="form-check-label" for="remember" style="vertical-align: middle;">
                                {{ trans('global.remember_me') }}
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-6">
                            <button type="submit" class="btn btn-primary px-4" id="login">
                                {{ trans('global.login') }}
                            </button>
                        </div>
                        <div class="col-6 text-right">
                            @if(Route::has('forget.password.get'))
                                <a class="btn btn-link px-0" href="{{ route('forget.password.get') }}">
                                    {{ trans('global.forgot_password') }}
                                </a><br>
                            @endif
                        </div>
                    </div>
                </form>
                @if(env('KEYCLOAK') === 'enable')
                    <!-- Ajout du bouton pour se connecter via Keycloak -->
                    <div class="text-right mt-3">
                        <a href="{{ route('login.keycloak') }}" class="btn btn-secondary">{{ (env('KEYCLOAK_BTN_LABEL') === null) ? "Keycloak" : env('KEYCLOAK_BTN_LABEL') }}</a>
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
