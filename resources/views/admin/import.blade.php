@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
            {{ trans("cruds.configuration.import.title") }}
            </div>

            <div class="card-body">
                <div class="row mb-4">
                    <div class="col-12">
                        {!! trans("cruds.configuration.import.description") !!}
                    </div>
                </div>

                {{-- Formulaire d'export --}}
                <form action="{{ route('admin.config.export') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="model" id="export-model" value="{{ old('model') }}">

                    <div class="row align-items-center">
                        <div class="col-md-3">
                            <label for="filters" class="form-label">{{ trans("cruds.configuration.import.filter") }}</label>
                            <select class="form-select select2" id="filters" name="filter">
                                <option></option>
                                <option value="1" {{ old('filter')=='1' ? "selected" : "" }}>{{ trans("cruds.report.cartography.ecosystem") }}</option>
                                <option value="2" {{ old('filter')=='2' ? "selected" : "" }}>{{ trans("cruds.report.cartography.information_system") }}</option>
                                <option value="3" {{ old('filter')=='3' ? "selected" : "" }}>{{ trans("cruds.report.cartography.applications") }}</option>
                                <option value="4" {{ old('filter')=='4' ? "selected" : "" }}>{{ trans("cruds.report.cartography.administration") }}</option>
                                <option value="5" {{ old('filter')=='5' ? "selected" : "" }}>{{ trans("cruds.report.cartography.logical_infrastructure") }}</option>
                                <option value="6" {{ old('filter')=='6' ? "selected" : "" }}>{{ trans("cruds.report.cartography.physical_infrastructure") }}</option>
                            </select>
                            <div class="help-block">{{ trans("cruds.configuration.import.filter_helper") }}</div>
                        </div>

                        <div class="col-md-3 node-container">
                        {{ old('node') }}
                            <label for="node" class="form-label">{{ trans("cruds.report.explorer.object") }}</label>
                            <select class="form-select select2" id="node">
                                <option></option>
                            </select>
                            <div class="help-block">{{ trans("cruds.configuration.import.choose") }}</div>
                        </div>

                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Export') }}
                            </button>
                        </div>
                    </div>
                </form>
                {{-- Formulaire d'import --}}
                <form action="{{ route('admin.config.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="model" id="import-model" value="{{ old('model') }}">

                    <div class="row align-items-center">
                        <div class="col-md-6">
                            <label for="file" class="form-label">{{ trans("cruds.configuration.import.file") }}</label>
                            <input type="file" name="file" id="file" value="{{ old('file') }}"class="form-control" accept=".xlsx,.csv" required>
                            <div class="help-block">{{ trans("cruds.configuration.import.file_helper") }}</div>
                        </div>

                        <div class="col-md-4 align-items-center">
                            <button type="submit" class="btn btn-success">
                                {{ __('Import') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
const objectMap = {
    1: [ // Écosystème
        { id: 'Relation', label: "{{ trans('cruds.relation.title') }}" },
        { id: 'Entity', label: "{{ trans('cruds.entity.title') }}" },
    ],
    2: [ // Système d'information
        { id: 'MacroProcessus', label: "{{ trans('cruds.macroProcessus.title') }}" },
        { id: 'Process', label: "{{ trans('cruds.process.title') }}" },
        { id: 'Activity', label: "{{ trans('cruds.activity.title') }}" },
        { id: 'Operation', label: "{{ trans('cruds.operation.title') }}" },
        { id: 'Task', label: "{{ trans('cruds.task.title') }}" },
        { id: 'Actor', label: "{{ trans('cruds.actor.title') }}" },
        { id: 'Information', label: "{{ trans('cruds.information.title') }}" },
    ],
    3: [ // Applications
        { id: 'ApplicationBlock', label: "{{ trans('cruds.applicationBlock.title') }}" },
        { id: 'MApplication', label: "{{ trans('cruds.application.title') }}" },
        { id: 'ApplicationService', label: "{{ trans('cruds.applicationService.title') }}" },
        { id: 'ApplicationModule', label: "{{ trans('cruds.applicationModule.title') }}" },
        { id: 'Database', label: "{{ trans('cruds.database.title') }}" },
        { id: 'LogicalFlow', label: "{{ trans('cruds.logicalFlow.title') }}" }
    ],
    4: [ // Administration
        { id: 'ZoneAdmin', label: "{{ trans('cruds.zoneAdmin.title') }}" },
        { id: 'Annuaire', label: "{{ trans('cruds.annuaire.title') }}" },
        { id: 'ForestAd', label: "{{ trans('cruds.forestAd.title') }}" },
        { id: 'DomaineAd', label: "{{ trans('cruds.domaineAd.title') }}" },
        { id: 'AdminUser', label: "{{ trans('cruds.adminUser.title') }}" },
    ],
    5: [ // Infrastructure Logique
        { id: 'Network', label: "{{ trans('cruds.network.title') }}" },
        { id: 'Subnetwork', label: "{{ trans('cruds.subnetwork.title') }}" },
        { id: 'Gateway', label: "{{ trans('cruds.gateway.title') }}" },
        { id: 'ExternalConnectedEntity', label: "{{ trans('cruds.externalConnectedEntity.title') }}" },
        { id: 'Router', label: "{{ trans('cruds.router.title') }}" },
        { id: 'NetworkSwitch', label: "{{ trans('cruds.networkSwitch.title') }}" },
        { id: 'SecurityDevice', label: "{{ trans('cruds.securityDevice.title') }}" },
        { id: 'Dnsserver', label: "{{ trans('cruds.dnsserver.title') }}" },
        { id: 'DhcpServer', label: "{{ trans('cruds.dhcpServer.title') }}" },
        { id: 'Cluster', label: "{{ trans('cruds.cluster.title') }}" },
        { id: 'LogicalServer', label: "{{ trans('cruds.logicalServer.title') }}" },
        { id: 'Container', label: "{{ trans('cruds.container.title') }}" },
        { id: 'Flux', label: "{{ trans('cruds.flux.title') }}" },
        { id: 'Vlan', label: "{{ trans('cruds.vlan.title') }}" },
        { id: 'Certificate', label: "{{ trans('cruds.certificate.title') }}" },
    ],
    6: [ // Infrastructure physique
        { id: 'Site', label: "{{ trans('cruds.site.title') }}" },
        { id: 'Building', label: "{{ trans('cruds.building.title') }}" },
        { id: 'Bay', label: "{{ trans('cruds.bay.title') }}" },
        { id: 'PhysicalServer', label: "{{ trans('cruds.physicalServer.title') }}" },
        { id: 'Workstation', label: "{{ trans('cruds.workstation.title') }}" },
        { id: 'StorageDevice', label: "{{ trans('cruds.storageDevice.title') }}" },
        { id: 'Peripheral', label: "{{ trans('cruds.peripheral.title') }}" },
        { id: 'Phone', label: "{{ trans('cruds.phone.title') }}" },
        { id: 'PhysicalRouter', label: "{{ trans('cruds.physicalRouter.title') }}" },
        { id: 'Switch', label: "{{ trans('cruds.physicalSwitch.title') }}" },
        { id: 'PhysicalSecurityDevice', label: "{{ trans('cruds.physicalSecurityDevice.title') }}" },
        { id: 'WifiTerminal', label: "{{ trans('cruds.wifiTerminal.title') }}" },
        { id: 'PhysicalLink', label: "{{ trans('cruds.physicalLink.title') }}" },
        { id: 'Wan', label: "{{ trans('cruds.wan.title') }}" },
        { id: 'Man', label: "{{ trans('cruds.man.title') }}" },
        { id: 'Lan', label: "{{ trans('cruds.lan.title') }}" },
    ],
};

function findFilterForModel(modelId) {
    for (const [filterId, models] of Object.entries(objectMap)) {
        if (models.some(m => m.id === modelId)) {
            return filterId;
        }
    }
    return null;
}

document.addEventListener("DOMContentLoaded", function () {
    const allObjects = Object.values(objectMap).flat();

    const $filters = $('#filters');
    const $node = $('#node');
    const $exportModel = $('#export-model');
    const $importModel = $('#import-model');

    const selectedModel = @json(old('model'));
    const selectedFilter = findFilterForModel(selectedModel);

    function updateNodeOptions(objects, selectedValue = null) {
        $node.empty();
        $node.append('<option disabled>{{ __("Sélectionnez un objet") }}</option>');

        objects.forEach(obj => {
            const isSelected = obj.id === selectedValue ? 'selected' : '';
            $node.append(`<option value="${obj.id}" ${isSelected}>${obj.label}</option>`);
        });

        $node.val(selectedValue).trigger('change');
        $exportModel.val(selectedValue);
        $importModel.val(selectedValue);
    }

    $filters.select2();
    $node.select2();

    if (selectedFilter) {
        $filters.val(selectedFilter).trigger('change');
        updateNodeOptions(objectMap[selectedFilter], selectedModel);
    } else {
        updateNodeOptions(allObjects, selectedModel);
    }

    $filters.on('change', function () {
        const selected = $(this).val();
        const list = objectMap[selected] || allObjects;
        updateNodeOptions(list);
    });

    $node.on('change', function () {
        const value = $(this).val();
        $exportModel.val(value);
        $importModel.val(value);
    });
});
</script>
@endsection
