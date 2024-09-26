@extends('layouts.app')
@section('styles')
<style>
     body {
                background-image: url('/images/mercator.png');
                background-size:  800px 800px;
                background-position: center;
                background-repeat: no-repeat;
                background-color: #3b2924;
            }

        .card {
            background-color: rgba(255, 255, 255, 0.5); /* Couleur blanche avec 80% d'opacité */
        }

        .input-group-text {
            background-color: rgba(255, 255, 255, 0.5); /* Fond partiellement transparent pour les icônes */
        }

        .form-control {
            background-color: rgba(255, 255, 255, 0.7); /* Fond partiellement transparent pour les champs de texte */
        }

        h1 {
            text-align: center;
            color: #333; /* Couleur du titre */
        }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card mx-4">
            <div class="card-body p-4">

                <p class="text-muted">{{ trans('global.reset_password') }}</p>

                @if(session('message'))
                    <div class="alert alert-success" role="alert">
                        {{ session('message') }}
                    </div>
                @endif

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
