<?php


namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Models\Actor;
use App\Models\Information;
use App\Models\MacroProcessus;
use App\Models\Operation;
use App\Models\Process;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class InformationSystemView extends Controller
{
    public function generate(Request $request): View
    {
        abort_if(Gate::denies('reports_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->macroprocess == null) {
            $request->session()->put('macroprocess', null);
            $macroprocess = null;
            $request->session()->put('process', null);
            $process = null;
        } else {
            if ($request->macroprocess != null) {
                $macroprocess = intval($request->macroprocess);
                $request->session()->put('macroprocess', $macroprocess);
            } else {
                $macroprocess = $request->session()->get('macroprocess');
            }

            if ($request->process == null) {
                $request->session()->put('process', null);
                $process = null;
            } elseif ($request->process != null) {
                $process = intval($request->process);
                $request->session()->put('process', $process);
            } else {
                $process = $request->session()->get('process');
            }
        }

        $all_macroprocess = MacroProcessus::All()->sortBy('name');

        if ($macroprocess !== null) {
            $macroProcessuses = MacroProcessus::where('macro_processuses.id', $macroprocess)->get();

            // TODO : improve me
            $processes = Process::All()->sortBy('name')
                ->filter(function ($item) use ($macroProcessuses, $process) {
                    if ($process !== null) {
                        return $item->id === $process;
                    }
                    foreach ($macroProcessuses as $macroprocess) {
                        foreach ($macroprocess->processes as $process) {
                            if ($item->id === $process->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $all_process = Process::All()->sortBy('name')
                ->filter(function ($item) use ($macroProcessuses, $process) {
                    foreach ($macroProcessuses as $macroprocess) {
                        foreach ($macroprocess->processes as $process) {
                            if ($item->id === $process->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $activities = Activity::All()->sortBy('name')
                ->filter(function ($item) use ($processes) {
                    foreach ($item->processes as $p) {
                        foreach ($processes as $process) {
                            if ($p->id === $process->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $operations = Operation::All()->sortBy('name')
                ->filter(function ($item) use ($activities) {
                    foreach ($item->activities as $o) {
                        foreach ($activities as $activity) {
                            if ($o->id === $activity->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $tasks = Task::All()->sortBy('name')
                ->filter(function ($item) use ($operations) {
                    foreach ($operations as $operation) {
                        foreach ($operation->tasks as $task) {
                            if ($item->id === $task->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $actors = Actor::All()->sortBy('name')
                ->filter(function ($item) use ($operations) {
                    foreach ($operations as $operation) {
                        foreach ($operation->actors as $actor) {
                            if ($item->id === $actor->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });

            // TODO : improve me
            $informations = Information::All()->sortBy('name')
                ->filter(function ($item) use ($processes) {
                    foreach ($processes as $process) {
                        foreach ($process->information as $information) {
                            if ($item->id === $information->id) {
                                return true;
                            }
                        }
                    }

                    return false;
                });
        } else {
            $macroProcessuses = MacroProcessus::All()->sortBy('name');
            $processes = Process::All()->sortBy('name');
            $activities = Activity::All()->sortBy('name');
            $operations = Operation::All()->sortBy('name');
            $tasks = Task::All()->sortBy('name');
            $actors = Actor::All()->sortBy('name');
            $informations = Information::All()->sortBy('name');
            $all_process = null;
        }

        return view('admin/reports/information_system')
            ->with('all_macroprocess', $all_macroprocess)
            ->with('macroProcessuses', $macroProcessuses)
            ->with('processes', $processes)
            ->with('all_process', $all_process)
            ->with('activities', $activities)
            ->with('operations', $operations)
            ->with('tasks', $tasks)
            ->with('actors', $actors)
            ->with('informations', $informations);
    }
}
