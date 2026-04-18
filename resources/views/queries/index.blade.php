@extends('layouts.admin')

@section('title', __('Requêtes sauvegardées'))

@section('content')
<div style="margin-bottom: 10px;" class="row">
    <div class="col-lg-12">
        <a id="btn-new" class="btn btn-success" href="{{ route('admin.queries.create') }}">
            {{ trans('global.add') }} @lang('query')
        </a>
    </div>
</div>


<div class="card">
    <div class="card-header">
        @lang('Queries')
    </div>

    {{-- Tableau --}}
    <div class="card">
    <div class="card-body">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>@lang('Nom')</th>
                        <th>@lang('Description')</th>
                        <th>@lang('Entité')</th>
                        <th>@lang('Sortie')</th>
                        <th>@lang('Propriétaire')</th>
                        <th>@lang('Partagée')</th>
                        <th class="text-end">@lang('Actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($queries as $q)
                    <tr data-entry-id="{{ $q->id }}">
                        <td></td>
                        <td>
                            <a href="{{ route('admin.queries.show', ['query' => $q->id]) }}"
                               title="@lang('Exécuter cette requête')">
                                {{ $q->name }}
                            </a>
                        </td>
                        <td class="text-muted small">{{ $q->description }}</td>
                        <td><code>{{ $q->from }}</code></td>
                        <td>
                            @if ($q->output === 'graph')
                                <span class="badge bg-info text-dark">
                                    <i class="fas fa-project-diagram"></i> Graphe
                                </span>
                            @else
                                <span class="badge bg-secondary">
                                    <i class="fas fa-list"></i> Liste
                                </span>
                            @endif
                        </td>
                        <td class="small">{{ $q->user?->name ?? '—' }}</td>
                        <td class="text-center">
                            @if ($q->is_public)
                                <i class="fas fa-check text-success" title="@lang('Partagée')"></i>
                            @else
                                <i class="fas fa-lock text-muted" title="@lang('Privée')"></i>
                            @endif
                        </td>
                        <td class="text-end text-nowrap">
                            {{-- Exécuter --}}
                            <a class="btn btn-xs btn-primary" href="{{ route('admin.queries.edit', ['query' => $q->id]) }}">
                                {{ trans('global.edit') }}
                            </a>
                            {{-- Supprimer --}}
                            <form action="{{ route('admin.queries.destroy', $q->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-4">
                            @lang('Aucune requête sauvegardée.')
                            <a href="{{ route('admin.queries.create') }}">@lang('Créer la première ?')</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => trans("cruds.queries.title_singular"),
    'URL' => route('admin.queries.massDestroy'),
    // 'canDelete' => auth()->user()->can('queries_delete') ? true : false
    'canDelete' => true
));
</script>
@endsection