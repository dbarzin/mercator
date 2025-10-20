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
use Symfony\Component\HttpFoundation\Response;

class ApplicationView extends Controller
{
    public function generate(Request $request)
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->applicationBlock === null) {
            $request->session()->put('applicationBlock', null);
            $applicationBlock = null;
            $request->session()->put('application', null);
            $application = null;
        } else {
            if ($request->applicationBlock !== null) {
                $applicationBlock = intval($request->applicationBlock);
                $request->session()->put('applicationBlock', $applicationBlock);
            } else {
                $applicationBlock = $request->session()->get('applicationBlock');
            }

            if ($request->application === null) {
                $request->session()->put('application', null);
                $application = null;
            } elseif ($request->application !== null) {
                $application = intval($request->application);
                $request->session()->put('application', $application);
            } else {
                $application = $request->session()->get('application');
            }
        }

        $all_applicationBlocks = ApplicationBlock::All()->sortBy('name');

        if ($applicationBlock !== null) {
            $applicationBlocks = ApplicationBlock::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock) {
                    return $item->id === $applicationBlock;
                });

            $applications = MApplication::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock, $application) {
                    if ($application !== null) {
                        return $item->id === $application;
                    }

                    return $item->application_block_id = $applicationBlock;
                });

            $all_applications = MApplication::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock) {
                    return $item->application_block_id === $applicationBlock;
                });

            $applications = MApplication::All()->sortBy('name')
                ->filter(function ($item) use ($applicationBlock, $application) {
                    if ($application === null) {
                        return $item->application_block_id === $applicationBlock;
                    }

                    return $item->id === $application;
                });

            $applicationServices = ApplicationService::All()->sortBy('name')
                ->filter(function ($item) use ($applications) {
                    foreach ($applications as $application) {
                        foreach ($application->services as $service) {
                            if ($item->id === $service->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            $applicationModules = ApplicationModule::All()->sortBy('name')
                ->filter(function ($item) use ($applicationServices) {
                    foreach ($applicationServices as $service) {
                        foreach ($service->modules as $module) {
                            if ($item->id === $module->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            $databases = Database::All()->sortBy('name')
                ->filter(function ($item) use ($applications) {
                    foreach ($applications as $application) {
                        foreach ($application->databases as $database) {
                            if ($item->id === $database->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $fluxes = Flux::All()->sortBy('name')
                ->filter(function ($item) use ($applications, $applicationModules, $databases) {
                    foreach ($applications as $application) {
                        if ($item->application_source_id === $application->id) {
                            return true;
                        }
                        if ($item->application_dest_id === $application->id) {
                            return true;
                        }
                    }
                    foreach ($applicationModules as $module) {
                        if ($item->module_source_id === $module->id) {
                            return true;
                        }
                        if ($item->module_dest_id === $module->id) {
                            return true;
                        }
                    }
                    foreach ($databases as $database) {
                        if ($item->database_source_id === $database->id) {
                            return true;
                        }
                        if ($item->database_dest_id === $database->id) {
                            return true;
                        }
                    }

                    return false;
                });
        } else {
            $applicationBlocks = ApplicationBlock::All()->sortBy('name');
            $applications = MApplication::All()->sortBy('name');
            $applicationServices = ApplicationService::All()->sortBy('name');
            $applicationModules = ApplicationModule::All()->sortBy('name');
            $databases = Database::All()->sortBy('name');
            $fluxes = Flux::All()->sortBy('name');
            $all_applications = null;
        }

        return view('admin/reports/applications')
            ->with('all_applicationBlocks', $all_applicationBlocks)
            ->with('all_applications', $all_applications)
            ->with('applicationBlocks', $applicationBlocks)
            ->with('applications', $applications)
            ->with('applicationServices', $applicationServices)
            ->with('applicationModules', $applicationModules)
            ->with('databases', $databases)
            ->with('fluxes', $fluxes);
    }
}
