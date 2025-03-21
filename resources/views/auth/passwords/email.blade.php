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

                <p class="text-muted">{{ trans('global.reset_password') }}</p>

                <form method="POST" action="{{ route('forget.password.post') }}">
                    @csrf

                    <div class="form-group">
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" value="{{ old('email') }}">

                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary btn-flat btn-block">
                                {{ trans('global.send_password') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
