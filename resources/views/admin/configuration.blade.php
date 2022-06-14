@extends('layouts.admin')
@section('content')

<div class="card">

    <div class="card-header">
        Configuration
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.certificate.save') }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <label><b>{{ trans("cruds.configuration.certificate.title") }}</b></label>
            
            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.certificate.message_subject") }}</label>
            <input class="form-control" type="text" name="mail_subject" id="mail_subject" value="{{ $mail_subject }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.certificate.sent_from") }}</label>
            <input class="form-control" type="text" name="mail_from" id="mail_from" value="{{ $mail_from }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.certificate.to") }}</label>
            <input class="form-control" type="text" name="mail_to" id="mail_to" value="{{ $mail_to }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.certificate.delay") }}</label>
            <select class="form-control select2" name="expire_delay" id="expire_delay">
                <option value="1" {{ $expire_delay=="1" ? 'selected' : '' }}>1 {{ trans("global.day") }}</option>
                <option value="7" {{ $expire_delay=="7" ? 'selected' : '' }}>7 {{ trans("global.days") }}</option>
                <option value="15" {{ $expire_delay=="15" ? 'selected' : '' }}>15 {{ trans("global.days") }}</option>
                <option value="30" {{ $expire_delay=="30" ? 'selected' : '' }}>1 {{ trans("global.month") }}</option>
                <option value="60" {{ $expire_delay=="60" ? 'selected' : '' }}>2 {{ trans("global.months") }}</option>
                <option value="90" {{ $expire_delay=="90" ? 'selected' : '' }}>3 {{ trans("global.months") }}</option>
            </select>
            </div>

            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.certificate.recurence") }}</label>
            <select class="form-control select2" name="check_frequency" id="check_frequency">
                <option value="0" {{ $check_frequency=="0" ? 'selected' : '' }}>{{ trans("global.never") }}</option>
                <option value="1" {{ $check_frequency=="1" ? 'selected' : '' }}>{{ trans("global.day") }}</option>
                <option value="7" {{ $check_frequency=="7" ? 'selected' : '' }}>{{ trans("global.week") }}</option>
                <option value="30" {{ $check_frequency=="30" ? 'selected' : '' }}>{{ trans("global.month") }}</option>
            </select>
            </div>

            <div class="form-group">
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="group" id="certRadios1" value="0" {{ $group==='0' ? 'checked' : '' }}>
                  <label class="form-check-label" for="certRadios1">
                    {{ trans("cruds.configuration.certificate.one_mail") }}
                  </label>
                </div>
                <div class="form-check">
                  <input class="form-check-input" type="radio" name="group" id="certRadios2" value="1" {{ $group==='1' ? 'checked' : '' }}>
                  <label class="form-check-label" for="certRadios2">
                    {{ trans("cruds.configuration.certificate.multiple_mails") }}
                  </label>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit" name="action" value="save">
                    {{ trans('global.save') }}
                </button>
                <button class="btn btn-success" type="submit" name="action" value="test">
                    {{ trans('global.test') }}
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
