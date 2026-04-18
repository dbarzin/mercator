@extends('layouts.admin')

@section('content')


<form action="{{ $query->exists
                    ? route('admin.queries.update', $query)
                    : route('admin.queries.store') }}"
      method="POST" id="saved-query-form">
    @csrf
    @if ($query->exists)
        @method('PUT')
    @endif

<div class="card">
    <div class="card-body">
        <div class="row">

            {{-- ── Colonne gauche : méta-données ────────────────────── --}}
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-header">@lang('Informations')</div>
                    <div class="card-body">

                        <div class="mb-3">
                            <label for="name" class="form-label fw-bold">@lang('Nom') <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                   id="name" name="name"
                                   value="{{ old('name', $query->name) }}"
                                   required autofocus>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">@lang('Description')</label>
                            <textarea class="form-control @error('description') is-invalid @enderror"
                                      id="description" name="description"
                                      rows="3">{{ old('description', $query->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox"
                                   id="is_public" name="is_public" value="1"
                                   {{ old('is_public', $query->is_public) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_public">
                                @lang('Partagée (visible par tous)')
                            </label>
                        </div>

                    </div>
                </div>
            </div>

            {{-- ── Colonne droite : DSL JSON ─────────────────────────── --}}
            <div class="col-md-8">
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <span>@lang('DSL de la requête (JSON)')</span>
                        <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-format-json">
                            <i class="fas fa-indent"></i> @lang('Formater')
                        </button>
                    </div>
                    <div class="card-body p-0">
                        @php
                            // old('query') peut être un array (après prepareForValidation)
                            // ou null (premier affichage)
                            $queryOld = old('query');
                            $queryJson = is_array($queryOld)
                                ? json_encode($queryOld, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                : json_encode($query->query ?? [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                        @endphp
                        <textarea class="form-control font-monospace border-0 rounded-0
                                         @error('query') is-invalid @enderror"
                                  id="query-json" name="query_json"
                                  rows="22"
                                  style="resize: vertical; font-size: 0.85rem;"
                                  spellcheck="false">{{ $queryJson }}</textarea>
                        @error('query')
                            <div class="invalid-feedback d-block px-3">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="card-footer text-muted small">
                        @lang('Exemple de structure :')
                        <code>{"from":"LogicalServer","filters":[],"traverse":["applications"],"depth":2,"output":"graph"}</code>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    <div class="form-group">
        <a id="btn-cancel" class="btn btn-default" href="{{ route('admin.queries.index') }}">
            {{ trans('global.back_to_list') }}
        </a>

        <button id="btn-save" class="btn btn-success" type="submit">
            {{ trans('global.save') }}
        </button>

    </div>
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    const textarea = document.getElementById('query-json');
    const form     = document.getElementById('saved-query-form');

    // ── Formater le JSON ────────────────────────────────────────
    document.getElementById('btn-format-json').addEventListener('click', function () {
        try {
            const parsed   = JSON.parse(textarea.value);
            textarea.value = JSON.stringify(parsed, null, 2);
            textarea.classList.remove('is-invalid');
        } catch (e) {
            textarea.classList.add('is-invalid');
            alert('@lang("JSON invalide :") ' + e.message);
        }
    });

    // ── Validation côté client avant submit ─────────────────────
    form.addEventListener('submit', function (e) {
        try {
            const parsed = JSON.parse(textarea.value);

            if (!parsed.from) {
                throw new Error('@lang("Le champ « from » est obligatoire.")');
            }
            if (parsed.output && !['graph', 'list'].includes(parsed.output)) {
                throw new Error('@lang("« output » doit être « graph » ou « list ».")');
            }

            // Compacter le JSON avant envoi (pas de whitespace inutile)
            textarea.value = JSON.stringify(parsed);
            textarea.classList.remove('is-invalid');

        } catch (e) {
            e.preventDefault();
            textarea.classList.add('is-invalid');
            textarea.focus();
            alert('@lang("Erreur dans le JSON :") ' + e.message);
        }
    });

    // ── Retrait de la marque d'erreur à la frappe ───────────────
    textarea.addEventListener('input', function () {
        textarea.classList.remove('is-invalid');
    });
});
</script>
@endpush