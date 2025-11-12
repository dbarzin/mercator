<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\ApplicationBlock;
use App\Models\ApplicationModule;
use App\Models\ApplicationService;
use App\Models\Database;
use App\Models\Flux;
use App\Models\MApplication;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ApplicationFlowView extends Controller
{
    public function generate(Request $request)
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Blocks
        if ($request->applicationBlocks == null) {
            $applicationBlocks = [];
            $request->session()->put('applicationBlocks', []);
        } else {
            if ($request->applicationBlocks != null) {
                $applicationBlocks = $request->applicationBlocks;
                $request->session()->put('applicationBlocks', $applicationBlocks);
            } else {
                $applicationBlocks = $request->session()->get('applicationBlocks');
            }
        }

        // Applications
        if ($request->applications == null) {
            $request->session()->put('applications', []);
            $applications = [];
        } else {
            if ($request->applications != null) {
                $applications = $request->applications;
                $request->session()->put('applications', $applications);
            } else {
                $applications = $request->session()->get('applications');
            }
        }

        // Databases
        if ($request->databases == null) {
            $request->session()->put('databases', []);
            $databases = [];
        } else {
            if ($request->databases != null) {
                $databases = $request->databases;
                $request->session()->put('databases', $databases);
            } else {
                $databases = $request->session()->get('databases');
            }
        }

        // Get assets
        $application_ids = DB::table('m_applications')
            ->whereIn('application_block_id', $applicationBlocks)
            ->whereNull('deleted_at')
            ->orWhereIn('id', $applications)
            ->pluck('id');

        $applicationservice_ids = DB::table('m_applications')
            ->join('application_service_m_application', 'm_applications.id', '=', 'application_service_m_application.m_application_id')
            ->whereIn('application_block_id', $applicationBlocks)
            ->whereNull('deleted_at')
            ->pluck('application_service_id')
            ->unique();

        $applicationmodule_ids = DB::table('m_applications')
            ->join('application_service_m_application', 'm_applications.id', '=', 'application_service_m_application.m_application_id')
            ->join('application_module_application_service', 'application_service_m_application.application_service_id', '=', 'application_module_application_service.application_service_id')
            ->whereNull('deleted_at')
            ->whereIn('application_block_id', $applicationBlocks)
            ->pluck('application_module_id')
            ->unique();

        $database_ids = collect($databases);

        // get all flows
        $flows = Flux::All()->sortBy('name');

        // Filter Flows
        $flows = $flows
            ->filter(function ($item) use (
                $application_ids,
                $applicationservice_ids,
                $applicationmodule_ids,
                $database_ids
            ) {
                return // application
                    $application_ids->contains($item->application_source_id) ||
                    $application_ids->contains($item->application_dest_id) ||
                    // service
                    $applicationservice_ids->contains($item->service_source_id) ||
                    $applicationservice_ids->contains($item->service_dest_id) ||
                    // module
                    $applicationmodule_ids->contains($item->module_source_id) ||
                    $applicationmodule_ids->contains($item->module_dest_id) ||
                    // database
                    $database_ids->contains($item->database_source_id) ||
                    $database_ids->contains($item->database_dest_id);
            });

        // filter linked objects
        $application_ids = collect();
        $service_ids = collect();
        $module_ids = collect();

        // loop on flows
        foreach ($flows as $flux) {
            // applications
            if (($flux->application_source_id !== null) &&
               (! $application_ids->contains($flux->application_source_id))) {
                $application_ids->push($flux->application_source_id);
            }
            if (($flux->application_dest_id !== null) &&
               (! $application_ids->contains($flux->application_dest_id))) {
                $application_ids->push($flux->application_dest_id);
            }

            // services
            if (($flux->service_source_id !== null) &&
               (! $service_ids->contains($flux->service_source_id))) {
                $service_ids->push($flux->service_source_id);
            }
            if (($flux->service_dest_id !== null) &&
               (! $service_ids->contains($flux->service_dest_id))) {
                $service_ids->push($flux->service_dest_id);
            }

            // modules
            if (($flux->module_source_id !== null) &&
               (! $module_ids->contains($flux->module_source_id))) {
                $module_ids->push($flux->module_source_id);
            }
            if (($flux->module_dest_id !== null) &&
               (! $module_ids->contains($flux->module_dest_id))) {
                $module_ids->push($flux->module_dest_id);
            }

            // databases
            if (($flux->database_source_id !== null) &&
               (! $database_ids->contains($flux->database_source_id))) {
                $database_ids->push($flux->database_source_id);
            }
            if (($flux->database_dest_id !== null) &&
               (! $database_ids->contains($flux->database_dest_id))) {
                $database_ids->push($flux->database_dest_id);
            }
        }

        // get objects
        $applications = MApplication::All()
            ->whereIn('id', $application_ids)
            ->sortBy('name');
        $applicationServices = ApplicationService::All()
            ->whereIn('id', $service_ids)
            ->sortBy('name');
        $applicationModules = ApplicationModule::All()
            ->whereIn('id', $module_ids)
            ->sortBy('name');
        $databases = Database::All()
            ->whereIn('id', $database_ids)
            ->sortBy('name');

        // update lists
        $all_applicationBlocks = ApplicationBlock::All()->sortBy('name')->pluck('name', 'id');
        $all_applications = MApplication::All()->sortBy('name')->pluck('name', 'id');
        $all_databases = Database::All()->sortBy('name')->pluck('name', 'id');

        // return
        return view('admin/reports/application_flows')
            ->with('all_applicationBlocks', $all_applicationBlocks)
            ->with('all_applications', $all_applications)
            ->with('all_databases', $all_databases)
            ->with('applications', $applications)
            ->with('applicationServices', $applicationServices)
            ->with('applicationModules', $applicationModules)
            ->with('databases', $databases)
            ->with('flows', $flows);
    }
}
