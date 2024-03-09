<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GlobalSearchController extends Controller
{
    private $models = [
        'Entity' => 'cruds.entity.title',
        'Relation' => 'cruds.relation.title',
        'Process' => 'cruds.process.title',
        'Operation' => 'cruds.operation.title',
        'Actor' => 'cruds.actor.title',
        'Activity' => 'cruds.activity.title',
        'Task' => 'cruds.task.title',
        'Information' => 'cruds.information.title',
        'ApplicationBlock' => 'cruds.applicationBlock.title',
        'MApplication' => 'cruds.application.title',
        'ApplicationService' => 'cruds.applicationService.title',
        'Database' => 'cruds.database.title',
        'Flux' => 'cruds.flux.title',
        'ZoneAdmin' => 'cruds.zoneAdmin.title',
        'Annuaire' => 'cruds.annuaire.title',
        'ForestAd' => 'cruds.forestAd.title',
        'DomaineAd' => 'cruds.domaineAd.title',
        'Network' => 'cruds.network.title',
        'Subnetwork' => 'cruds.subnetwork.title',
        'Gateway' => 'cruds.gateway.title',
        'ExternalConnectedEntity' => 'cruds.externalConnectedEntity.title',
        'NetworkSwitch' => 'cruds.networkSwitch.title',
        'Router' => 'cruds.router.title',
        'SecurityDevice' => 'cruds.securityDevice.title',
        'DhcpServer' => 'cruds.dhcpServer.title',
        'Dnsserver' => 'cruds.dnsserver.title',
        'LogicalServer' => 'cruds.logicalServer.title',
        'Site' => 'cruds.site.title',
        'Building' => 'cruds.building.title',
        'Bay' => 'cruds.bay.title',
        'PhysicalServer' => 'cruds.physicalServer.title',
        'Workstation' => 'cruds.workstation.title',
        'StorageDevice' => 'cruds.storageDevice.title',
        'Peripheral' => 'cruds.peripheral.title',
        'Phone' => 'cruds.phone.title',
        'PhysicalSwitch' => 'cruds.physicalSwitch.title',
        'PhysicalRouter' => 'cruds.physicalRouter.title',
        'WifiTerminal' => 'cruds.wifiTerminal.title',
        'PhysicalSecurityDevice' => 'cruds.physicalSecurityDevice.title',
        'Wan' => 'cruds.wan.title',
        'Man' => 'cruds.man.title',
        'Lan' => 'cruds.lan.title',
        'Vlan' => 'cruds.vlan.title',
        'ApplicationModule' => 'cruds.applicationModule.title',
        'MacroProcessus' => 'cruds.macroProcessus.title',
        'Certificate' => 'cruds.certificate.title',
        'Activity' => 'cruds.activity.title',
        'DataProcessing' => 'cruds.dataProcessing.title',
        'SecurityControl' => 'cruds.securityControl.title',
    ];

    public function search(Request $request)
    {
        $term = $request->input('search');

        $searchableData = [];

        if (($term === null) || (strlen($term) < 3)) {
            return view('admin.search', compact('searchableData'));
        }

        foreach ($this->models as $model => $translation) {
            $modelClass = 'App\\' . $model;
            $query = $modelClass::query();

            $fields = $modelClass::$searchable;

            foreach ($fields as $field) {
                $query->orWhere($field, 'LIKE', '%' . $term . '%');
            }

            $results = $query->take(100)->get();

            foreach ($results as $result) {
                $parsedData['data'] = $result->only($fields);
                $parsedData['model'] = $model;
                $parsedData['name'] = trans($translation);
                $parsedData['fields'] = $fields;
                $formattedFields = [];

                foreach ($fields as $field) {
                    $formattedFields[$field] = Str::title(str_replace('_', ' ', $field));
                }

                $parsedData['fields_formated'] = $formattedFields;

                // TODO: Fix me please (XXXX)
                $parsedData['url'] = '/admin/' .
                    ($model === 'MApplication' ? 'applications' : Str::plural(Str::snake($model, '-'))) .
                    '/' . $result->id;
                // to got to edit : . '/edit'

                $searchableData[] = $parsedData;
            }
        }

        return view('admin.search', compact('searchableData'));
    }
}
