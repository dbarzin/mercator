@extends('layouts.admin')

@section('title')
    {{ trans('cruds.configuration.parameters.title') }}
@endsection

@section('content')

{{-- ─── Alert messages ──────────────────────────────────────────────────── --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@php $tab = $active_tab ?? 'general'; @endphp

{{-- ─── Tab navigation ──────────────────────────────────────────────────── --}}
<ul class="nav nav-tabs" id="configTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold {{ $tab === 'general' ? 'active' : '' }}"
                id="tab-general-btn" data-bs-toggle="tab" data-bs-target="#tab-general"
                type="button" role="tab"
                aria-controls="tab-general" aria-selected="{{ $tab === 'general' ? 'true' : 'false' }}">
            <i class="fas fa-cog me-1"></i>
            {{ trans('cruds.configuration.parameters.title_short') }}
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold {{ $tab === 'cert' ? 'active' : '' }}"
                id="tab-cert-btn" data-bs-toggle="tab" data-bs-target="#tab-cert"
                type="button" role="tab"
                aria-controls="tab-cert" aria-selected="{{ $tab === 'cert' ? 'true' : 'false' }}">
            <i class="fas fa-shield-alt me-1"></i>
            {{ trans('cruds.configuration.certificate.title_short') }}
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold {{ $tab === 'cve' ? 'active' : '' }}"
                id="tab-cve-btn" data-bs-toggle="tab" data-bs-target="#tab-cve"
                type="button" role="tab"
                aria-controls="tab-cve" aria-selected="{{ $tab === 'cve' ? 'true' : 'false' }}">
            <i class="fas fa-bug me-1"></i>
            {{ trans('cruds.configuration.cve.title_short') }}
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold {{ $tab === 'documents' ? 'active' : '' }}"
                id="tab-documents-btn" data-bs-toggle="tab" data-bs-target="#tab-documents"
                type="button" role="tab"
                aria-controls="tab-documents" aria-selected="{{ $tab === 'documents' ? 'true' : 'false' }}">
            <i class="fas fa-file-alt me-1"></i>
            {{ trans('cruds.configuration.documents.title') }}
        </button>
    </li>
</ul>

{{-- ─── Tab content ─────────────────────────────────────────────────────── --}}
<div class="tab-content" id="configTabsContent">

    {{-- ================================================================== --}}
    {{-- TAB 1 : Paramètres généraux                                         --}}
    {{-- ================================================================== --}}
    <div class="tab-pane fade {{ $tab === 'general' ? 'show active' : '' }}"
         id="tab-general" role="tabpanel" aria-labelledby="tab-general-btn">

        <form method="POST" action="{{ route('admin.config.parameters') }}">
            @method('PUT')
            @csrf
            <input type="hidden" name="active_tab" value="general">

            <div class="card">
                <div class="card-body">
                    <div class="form-group mb-3">
                        <label>{{ trans('cruds.configuration.parameters.help') }}</label>
                    </div>
                </div>
                <div class="card-body border-top">
                    <h6 class="fw-bold mb-3">{{ trans('cruds.menu.logical_infrastructure.title_short') }}</h6>
                    <div class="form-group mb-3">
                        <label class="d-block mb-1">
                            {{ trans('cruds.configuration.parameters.security_need_auth_helper') }}
                        </label>
                        <div class="form-check form-switch">
                            <input name="security_need_auth" id="security_need_auth"
                                   type="checkbox" class="form-check-input"
                                   {{ $security_need_auth ? 'checked' : '' }}>
                            <label class="form-check-label" for="security_need_auth">
                                {{ trans('cruds.configuration.parameters.security_need_auth') }}
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group mt-3">
                <button class="btn btn-success" type="submit" name="action" value="save">
                    <i class="fas fa-save me-1"></i>{{ trans('global.save') }}
                </button>
                <a class="btn btn-default" href="{{ route('admin.home') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </form>

    </div>{{-- /tab-general --}}

    {{-- ================================================================== --}}
    {{-- TAB 2 : Certificats                                                 --}}
    {{-- ================================================================== --}}
    <div class="tab-pane fade {{ $tab === 'cert' ? 'show active' : '' }}"
         id="tab-cert" role="tabpanel" aria-labelledby="tab-cert-btn">

        <form method="POST" action="{{ route('admin.config.parameters') }}">
            @method('PUT')
            @csrf
            <input type="hidden" name="active_tab" value="cert">

            <div class="card">
                <div class="card-body">

                    <div class="form-group mb-3">
                        <label>{{ trans('cruds.configuration.certificate.help') }}</label>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cert_mail_subject">
                            {{ trans('cruds.configuration.certificate.message_subject') }}
                        </label>
                        <input class="form-control" type="text"
                               name="mail_subject" id="cert_mail_subject"
                               value="{{ $cert_mail_subject }}" required/>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cert_mail_from">
                            {{ trans('cruds.configuration.certificate.sent_from') }}
                        </label>
                        <input class="form-control" type="text"
                               name="mail_from" id="cert_mail_from"
                               value="{{ $cert_mail_from }}" required/>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cert_mail_to">
                            {{ trans('cruds.configuration.certificate.to') }}
                        </label>
                        <input class="form-control" type="text"
                               name="mail_to" id="cert_mail_to"
                               value="{{ $cert_mail_to }}" required/>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cert_expire_delay">
                            {{ trans('cruds.configuration.certificate.delay') }}
                        </label>
                        <select class="form-control select2" name="expire_delay" id="cert_expire_delay">
                            @foreach ([
                                '1'  => '1 '  . trans('global.day'),
                                '7'  => '7 '  . trans('global.days'),
                                '15' => '15 ' . trans('global.days'),
                                '30' => '1 '  . trans('global.month'),
                                '60' => '2 '  . trans('global.months'),
                                '90' => '3 '  . trans('global.months'),
                            ] as $val => $label)
                                <option value="{{ $val }}" {{ $cert_expire_delay == $val ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cert_check_frequency">
                            {{ trans('cruds.configuration.certificate.recurrence') }}
                        </label>
                        <select class="form-control select2" name="check_frequency" id="cert_check_frequency">
                            <option value="0"  {{ $cert_check_frequency == '0'  ? 'selected' : '' }}>{{ trans('global.never') }}</option>
                            <option value="1"  {{ $cert_check_frequency == '1'  ? 'selected' : '' }}>{{ trans('global.day') }}</option>
                            <option value="7"  {{ $cert_check_frequency == '7'  ? 'selected' : '' }}>{{ trans('global.week') }}</option>
                            <option value="30" {{ $cert_check_frequency == '30' ? 'selected' : '' }}>{{ trans('global.month') }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label class="d-block mb-1">
                            {{ trans('cruds.configuration.certificate.one_mail') }} /
                            {{ trans('cruds.configuration.certificate.multiple_mails') }}
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="group"
                                   id="certRadios1" value="0"
                                   {{ $cert_group === '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="certRadios1">
                                {{ trans('cruds.configuration.certificate.one_mail') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="group"
                                   id="certRadios2" value="1"
                                   {{ $cert_group === '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="certRadios2">
                                {{ trans('cruds.configuration.certificate.multiple_mails') }}
                            </label>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <label class="d-block mb-1">
                            {{ trans('cruds.configuration.certificate.one_notification') }} /
                            {{ trans('cruds.configuration.certificate.multiple_notifications') }}
                        </label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="repeat-notification"
                                   id="certRadios3" value="0"
                                   {{ $cert_repeat_notification === '0' ? 'checked' : '' }}>
                            <label class="form-check-label" for="certRadios3">
                                {{ trans('cruds.configuration.certificate.one_notification') }}
                            </label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="repeat-notification"
                                   id="certRadios4" value="1"
                                   {{ $cert_repeat_notification === '1' ? 'checked' : '' }}>
                            <label class="form-check-label" for="certRadios4">
                                {{ trans('cruds.configuration.certificate.multiple_notifications') }}
                            </label>
                        </div>
                    </div>

                </div>
            </div>

            <div class="form-group mt-3">
                <button class="btn btn-success" type="submit" name="action" value="save">
                    <i class="fas fa-save me-1"></i>{{ trans('global.save') }}
                </button>
                <button class="btn btn-secondary" type="submit" name="action" value="test">
                    <i class="fas fa-paper-plane me-1"></i>{{ trans('global.test') }}
                </button>
            </div>

        </form>

    </div>{{-- /tab-cert --}}

    {{-- ================================================================== --}}
    {{-- TAB 3 : CVE                                                         --}}
    {{-- ================================================================== --}}
    <div class="tab-pane fade {{ $tab === 'cve' ? 'show active' : '' }}"
         id="tab-cve" role="tabpanel" aria-labelledby="tab-cve-btn">

        <form method="POST" action="{{ route('admin.config.parameters') }}">
            @method('PUT')
            @csrf
            <input type="hidden" name="active_tab" value="cve">

            <div class="card">
                <div class="card-body">

                    <div class="form-group mb-3">
                        <label>{{ trans('cruds.configuration.cve.help') }}</label>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cve_mail_subject">
                            {{ trans('cruds.configuration.cve.message_subject') }}
                        </label>
                        <input class="form-control" type="text"
                               name="mail_subject" id="cve_mail_subject"
                               value="{{ $cve_mail_subject }}" required/>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cve_mail_from">
                            {{ trans('cruds.configuration.cve.sent_from') }}
                        </label>
                        <input class="form-control" type="text"
                               name="mail_from" id="cve_mail_from"
                               value="{{ $cve_mail_from }}" required/>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cve_mail_to">
                            {{ trans('cruds.configuration.cve.to') }}
                        </label>
                        <input class="form-control" type="text"
                               name="mail_to" id="cve_mail_to"
                               value="{{ $cve_mail_to }}" required/>
                    </div>

                    <div class="form-group mb-3">
                        <label class="label-required" for="cve_check_frequency">
                            {{ trans('cruds.configuration.cve.recurrence') }}
                        </label>
                        <select class="form-control select2" name="check_frequency" id="cve_check_frequency">
                            <option value="0"  {{ $cve_check_frequency == '0'  ? 'selected' : '' }}>{{ trans('global.never') }}</option>
                            <option value="1"  {{ $cve_check_frequency == '1'  ? 'selected' : '' }}>{{ trans('global.day') }}</option>
                            <option value="7"  {{ $cve_check_frequency == '7'  ? 'selected' : '' }}>{{ trans('global.week') }}</option>
                            <option value="30" {{ $cve_check_frequency == '30' ? 'selected' : '' }}>{{ trans('global.month') }}</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="cpe_guesser">
                            {{ trans('cruds.configuration.cpe.guesser') }}
                        </label>
                        <input class="form-control" type="text"
                               name="cpe_guesser" id="cpe_guesser"
                               value="{{ $cpe_guesser }}"/>
                        <span class="help-block text-muted small">
                            {{ trans('cruds.configuration.cpe.guesser_helper') }}
                        </span>
                    </div>


                    <div class="form-group mb-3">
                        <label for="cve_provider">
                            {{ trans('cruds.configuration.cve.provider') }}
                        </label>
                        <input class="form-control" type="text"
                               name="provider" id="cve_provider"
                               value="{{ $cve_provider }}"/>
                        <span class="help-block text-muted small">
                            {{ trans('cruds.configuration.cve.provider_helper') }}
                        </span>
                    </div>

                </div>
            </div>

            <div class="form-group mt-3">
                <button class="btn btn-success" type="submit" name="action" value="save">
                    <i class="fas fa-save me-1"></i>{{ trans('global.save') }}
                </button>
                <button class="btn btn-secondary" type="submit" name="action" value="test">
                    <i class="fas fa-paper-plane me-1"></i>{{ trans('global.test') }} Mail
                </button>
                <button class="btn btn-secondary" type="submit" name="action" value="test_provider">
                    <i class="fas fa-plug me-1"></i>{{ trans('global.test') }} Provider
                </button>
                <button class="btn btn-secondary" type="submit" name="action" value="test_guesser">
                    <i class="fas fa-plug me-1"></i>{{ trans('global.test') }} Guesser
                </button>
            </div>

        </form>

    </div>{{-- /tab-cve --}}

    {{-- ================================================================== --}}
    {{-- TAB 4 : Documents                                                   --}}
    {{-- ================================================================== --}}
    <div class="tab-pane fade {{ $tab === 'documents' ? 'show active' : '' }}"
         id="tab-documents" role="tabpanel" aria-labelledby="tab-documents-btn">

        <div class="card">
            <div class="card-body">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>{{ trans('cruds.configuration.documents.count') }}</span>
                                <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <span>{{ trans('cruds.configuration.documents.total_size') }}</span>
                                <span class="badge bg-secondary rounded-pill">
                                    @php
                                        $bytes = $sum;
                                        $units = ['B','KB','MB','GB','TB'];
                                        $i = 0;
                                        while ($bytes >= 1024 && $i < 4) { $bytes /= 1024; $i++; }
                                        echo round($bytes, 2) . ' ' . $units[$i];
                                    @endphp
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <form action="{{ route('admin.config.documents.check') }}" method="GET">
                    <button class="btn btn-success" type="submit">
                        <i class="fas fa-check-circle me-1"></i>{{ trans('global.check') }}
                    </button>
                </form>

                @if (isset($documents) && $documents->isNotEmpty())
                <div class="mt-4">
                    <h6 class="text-muted mb-2">
                        <i class="fas fa-list me-1"></i>{{ trans('cruds.document.list') }}
                    </h6>
                    <table class="table table-sm table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>{{ trans('cruds.configuration.documents.name') }}</th>
                                <th>{{ trans('cruds.configuration.documents.mimetype') }}</th>
                                <th>{{ trans('cruds.configuration.documents.size') }}</th>
                                <th>{{ trans('cruds.configuration.documents.hash') }}</th>
                                <th>{{ trans('cruds.configuration.documents.status') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($documents as $doc)
                            <tr>
                                <td>{{ $doc->id }}</td>
                                <td>
                                    <a href="{{ route('admin.documents.show', $doc->id) }}">
                                        {{ substr($doc->filename, 0, 64) }}
                                    </a>
                                </td>
                                <td>{{ $doc->mimetype }}</td>
                                <td>{{ $doc->humanSize() }}</td>
                                <td><code class="small">{{ $doc->hash }}</code></td>
                                <td>
                                    @php $path = storage_path('docs/') . $doc->id @endphp
                                    @if (!file_exists($path))
                                        <span class="badge bg-warning text-dark">MISSING</span>
                                    @elseif ($doc->hash === hash_file('sha256', $path))
                                        <span class="badge bg-success">OK</span>
                                    @else
                                        <span class="badge bg-danger">HASH FAILS</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>{{-- /tab-documents --}}
</div>{{-- /tab-content --}}

{{-- ─── Persistance de l'onglet lors de la navigation manuelle ─────────── --}}
@push('scripts')
<script>
(function () {
    'use strict';
    document.addEventListener('shown.bs.tab', function (e) {
        var id = e.target.getAttribute('data-bs-target').slice(1);
        sessionStorage.setItem('mercator_config_tab', id);
        history.replaceState(null, '', '#' + id);
    });
})();
</script>
@endpush

@endsection