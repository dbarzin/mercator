<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\DataProcessing;
use App\Models\MacroProcessus;
use App\Models\MApplication;
use App\Models\Process;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GDPRView extends Controller
{
    /*
    * GDPR
    */
    public function generate(Request $request)
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->macroprocess === null) {
            $request->session()->put('macroprocess', null);
            $macroprocess = null;
            $request->session()->put('process', null);
            $process = null;
        } else {
            if ($request->macroprocess !== null) {
                $macroprocess = intval($request->macroprocess);
                $request->session()->put('macroprocess', $macroprocess);
            } else {
                $macroprocess = $request->session()->get('macroprocess');
            }

            if ($request->process === null) {
                $request->session()->put('process', null);
                $process = null;
            } elseif ($request->process !== null) {
                $process = intval($request->process);
                $request->session()->put('process', $process);
            } else {
                $process = $request->session()->get('process');
            }
        }

        // All macroprocess with process having a data_processing
        $all_macroprocess = MacroProcessus::query()
            ->whereExists(function ($query) {
                $query->select('processes.id')
                    ->from('processes')
                    ->join('data_processing_process', 'processes.id', '=', 'data_processing_process.process_id')
                    ->join('data_processing', 'data_processing_process.data_processing_id', '=', 'data_processing.id')
                    ->whereNull('data_processing.deleted_at')
                    ->whereRaw('macro_processuses.id = processes.macroprocess_id');
            })
            ->orderBy('name')
            ->get();

        if ($macroprocess !== null) {
            $macroProcessuses = MacroProcessus::where('id', $macroprocess)
                ->get();

            $all_process = Process::orderBy('name')
                ->where('macroprocess_id', $macroprocess)
                ->whereExists(function ($query) {
                    $query->select('data_processing_process.process_id')
                        ->from('data_processing_process')
                        ->join('data_processing', 'data_processing_process.data_processing_id', '=', 'data_processing.id')
                        ->whereNull('data_processing.deleted_at')
                        ->whereRaw('data_processing_process.process_id = processes.id');
                })
                ->get();

            if ($process !== null) {
                // Data processing of this process
                $dataProcessings = DataProcessing::query()
                    ->whereExists(function ($query) use ($process) {
                        $query->select('data_processing_id')
                            ->from('data_processing_process')
                            ->where('data_processing_process.process_id', $process)
                            ->whereRaw('data_processing_process.data_processing_id = data_processing.id');
                    })
                    ->orderBy('name')
                    ->get();

                $processes = Process::where('id', $process)->get();
            } else {
                // Data processing for this macroprocess
                $dataProcessings = DataProcessing::query()
                    ->whereExists(function ($query) use ($macroprocess) {
                        $query->select('data_processing_id')
                            ->from('data_processing_process')
                            ->join('processes', 'processes.id', 'data_processing_process.process_id')
                            ->where('processes.macroprocess_id', $macroprocess)
                            ->whereRaw('data_processing_process.data_processing_id = data_processing.id');
                    })
                    ->orderBy('name')
                    ->get();
                $processes = $all_process;
            }
        } else {
            // only macroProcesses with data processisng
            $macroProcessuses = MacroProcessus::orderBy('name')
                ->whereExists(function ($query) {
                    $query->select('processes.id')
                        ->from('processes')
                        ->join('data_processing_process', 'data_processing_process.process_id', '=', 'processes.id')
                        ->join('data_processing', 'data_processing_process.data_processing_id', '=', 'data_processing.id')
                        ->whereNull('data_processing.deleted_at')
                        ->whereRaw('processes.macroprocess_id = macro_processuses.id');
                })
                ->get();

            // only process with data processisng
            $processes = Process::query()
                ->whereExists(function ($query) {
                    $query->select('data_processing_id')
                        ->from('data_processing_process')
                        ->join('data_processing', 'data_processing_process.data_processing_id', '=', 'data_processing.id')
                        ->whereNull('data_processing.deleted_at')
                        ->whereRaw('data_processing_process.process_id = processes.id');
                })
                ->orderBy('name')
                ->get();

            $dataProcessings = DataProcessing::query()
                ->orderBy('name')
                ->get();

            $all_process = Process::query()
                ->orderBy('name')
                ->where('macroprocess_id', $macroprocess)
                ->whereExists(function ($query) {
                    $query->select('data_processing_process.process_id')
                        ->from('data_processing_process')
                        ->whereRaw('data_processing_process.process_id = processes.id');
                })
                ->get();
        }

        // Select applications
        $applications = MApplication::query()
            ->join('data_processing_m_application', 'm_application_id', 'm_applications.id')
            ->wherein('data_processing_id', $dataProcessings->pluck('id')->all())->get();

        return view('admin/reports/gdpr')
            ->with('all_macroprocess', $all_macroprocess)
            ->with('macroProcessuses', $macroProcessuses)
            ->with('processes', $processes)
            ->with('all_process', $all_process)
            ->with('dataProcessings', $dataProcessings)
            ->with('applications', $applications);
    }
}
