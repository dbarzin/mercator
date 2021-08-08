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
            <select class="form-control select2" name="building_id" id="building_id">
                <option value="1">1 jour</option>
                <option value="7">7 jours</option>
                <option value="15">15 jours</option>
                <option value="30">1 mois</option>
                <option value="60">2 mois</option>
                <option value="90">3 mois</option>
            </select>
            </div>

            <div class="form-group">
            <label class="required" for="name">tout les</label>
            <select class="form-control select2" name="building_id" id="building_id">
                <option value="1">jour</option>
                <option value="2">semaine</option>
                <option value="3">mois</option>
            </select>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>

    </div>


</div>

@endsection