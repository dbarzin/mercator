@extends('layouts.admin')
@section('content')

<div class="card">

    <div class="card-header">
        {{ trans("cruds.configuration.cve.title") }}
    </div>

    <div class="card-body">

        <form method="POST" action="{{ route('admin.config.cve.save') }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf

            <div class="form-group">
            <label for="name">{{ trans("cruds.configuration.cve.help") }}</label>
            </div>
            
            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.cve.message_subject") }}</label>
            <input class="form-control" type="text" name="mail_subject" id="mail_subject" value="{{ $mail_subject }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.cve.sent_from") }}</label>
            <input class="form-control" type="text" name="mail_from" id="mail_from" value="{{ $mail_from }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.cve.to") }}</label>
            <input class="form-control" type="text" name="mail_to" id="mail_to" value="{{ $mail_to }}" required/>
            </div>

            <div class="form-group">
            <label class="required" for="name">{{ trans("cruds.configuration.cve.recurence") }}</label>
            <select class="form-control select2" name="check_frequency" id="check_frequency">
                <option value="0" {{ $check_frequency=="0" ? 'selected' : '' }}>{{ trans("global.never") }}</option>
                <option value="1" {{ $check_frequency=="1" ? 'selected' : '' }}>{{ trans("global.day") }}</option>
                <option value="7" {{ $check_frequency=="7" ? 'selected' : '' }}>{{ trans("global.week") }}</option>
                <option value="30" {{ $check_frequency=="30" ? 'selected' : '' }}>{{ trans("global.month") }}</option>
            </select>
            </div>

            <div class="form-group">
                <label class="required" for="name">{{ trans("cruds.configuration.cve.provider") }}</label>
                <input class="form-control" type="text" name="provider" id="provider" value="{{ $provider }}" required/>
                <span class="help-block">{{ trans('cruds.configuration.cve.provider_helper') }}</span>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit" name="action" value="save">
                    {{ trans('global.save') }}
                </button>
                <button class="btn btn-success" type="submit" name="action" value="test">
                    {{ trans('global.test') }} Mail
                </button>
                <button class="btn btn-success" type="submit" name="action" value="test_provider">
                    {{ trans('global.test') }} Provider
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
