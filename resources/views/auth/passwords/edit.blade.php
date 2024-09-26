@extends('layouts.admin')
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
<div class="card">
    <div class="card-header">
        {{ trans('global.change_password') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("profile.password.update") }}">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>
            @if (\Config::get('LDAP_DOMAIN')==null)
            <div class="form-group">
                <label class="required" for="title">New {{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" autocomplete="new-password" required>
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="title">Repeat New {{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" autocomplete="new-password" required>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
            @endif
        </form>
    </div>
</div>

@endsection
