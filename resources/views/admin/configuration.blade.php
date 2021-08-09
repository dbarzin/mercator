@extends('layouts.admin')
@section('content')

<div class="card">

    <div class="card-header">
        Configuration
    </div>

    <div class="card-body">

        <form method="POST" action="" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <label><b>Expiration des certificats</b></label>
            
            <div class="form-group">
            <label class="required" for="name">Titre du message</label>
            <input class="form-control" type="text" name="mail_subject" id="mail_subject" value="{{ $mail_subject }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">Enoyer depuis</label>
            <input class="form-control" type="text" name="mail_from" id="mail_from" value="{{ $mail_from }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">à</label>
            <input class="form-control" type="text" name="mail_to" id="mail_to" value="{{ $mail_to }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">les certificats qui expirent dans</label>
            <select class="form-control select2" name="check_frequency" id="check_frequency">
                <option value="1" {{ $check_frequency=="1" ? 'selected' : '' }}>1 jour</option>
                <option value="7" {{ $check_frequency=="7" ? 'selected' : '' }}>7 jours</option>
                <option value="15" {{ $check_frequency=="15" ? 'selected' : '' }}>15 jours</option>
                <option value="30" {{ $check_frequency=="30" ? 'selected' : '' }}>1 mois</option>
                <option value="60" {{ $check_frequency=="60" ? 'selected' : '' }}>2 mois</option>
                <option value="90" {{ $check_frequency=="90" ? 'selected' : '' }}>3 mois</option>
            </select>
            </div>

            <div class="form-group">
            <label class="required" for="name">tout les</label>
            <select class="form-control select2" name="expire_delay" id="expire_delay">
                <option value="0" {{ $expire_delay=="0" ? 'selected' : '' }}>jamais</option>
                <option value="1" {{ $expire_delay=="1" ? 'selected' : '' }}>jour</option>
                <option value="2" {{ $expire_delay=="2" ? 'selected' : '' }}>semaine</option>
                <option value="3" {{ $expire_delay=="3" ? 'selected' : '' }}>mois</option>
            </select>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit" name="action" value="save">
                    {{ trans('global.save') }}
                </button>
                <button class="btn btn-success" type="submit" name="action" value="test">
                    {{ trans('global.test') }}
                </button>
            </div>

    </div>


</div>

@endsection