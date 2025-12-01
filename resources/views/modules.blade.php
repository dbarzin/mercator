@extends('layouts.admin')

@section('content')
    <div class="card">
        <div class="card-header">
            Modules Mercator
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                    <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Label</th>
                        <th>Package</th>
                        <th>Version (package)</th>
                        <th>Version (DB)</th>
                        <th>Installé</th>
                        <th>Activé</th>
                        <th class="text-end">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($modules as $module)
                        <tr>
                            <td>{{ $module['name'] }}</td>
                            <td>{{ $module['label'] }}</td>
                            <td><code>{{ $module['package'] }}</code></td>
                            <td>{{ $module['version'] ?? '—' }}</td>
                            <td>{{ $module['db_version'] ?? '—' }}</td>
                            <td>
                                @if($module['installed'])
                                    <span class="badge bg-success">Installé</span>
                                @else
                                    <span class="badge bg-secondary">Non installé</span>
                                @endif
                            </td>
                            <td>
                                @if($module['installed'])
                                    @if($module['enabled'])
                                        <span class="badge bg-success">Activé</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Désactivé</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td class="text-end">
                                {{-- Installer --}}
                                @unless($module['installed'])
                                    <form action="{{ route('admin.modules.install', $module['name']) }}" method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-primary">Installer</button>
                                    </form>
                                @endunless

                                {{-- Activer / désactiver --}}
                                @if($module['installed'])
                                    @if(!$module['enabled'])
                                        <form action="{{ route('admin.modules.enable', $module['name']) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-success">Activer</button>
                                        </form>
                                    @else
                                        <form action="{{ route('admin.modules.disable', $module['name']) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            <button class="btn btn-sm btn-warning">Désactiver</button>
                                        </form>
                                    @endif
                                @endif

                                {{-- Désinstaller --}}
                                @if($module['installed'])
                                    <form action="{{ route('admin.modules.uninstall', $module['name']) }}" method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('{{ trans('cruds.module.confirm_uninstall') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">Désinstaller</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">
                                Aucun module détecté. Vérifie ton composer.json.
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
