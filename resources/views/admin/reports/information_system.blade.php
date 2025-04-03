@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                {{ trans('cruds.menu.metier.title') }}
            </div>

            <div class="card-body">
                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                @if (auth()->user()->granularity>=2)
                <div class="col-sm-5">
                    <form action="/admin/report/information_system">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td>
                                    {{ trans('cruds.macroProcessus.title') }} :
                                    <select name="macroprocess" onchange="this.form.process.value='';this.form.submit()">
                                        <option value="">-- All --</option>
                                        @foreach ($all_macroprocess as $macroprocess)
                                            <option value="{{$macroprocess->id}}" {{ Session::get('macroprocess')==$macroprocess->id ? "selected" : "" }}>{{ $macroprocess->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{ trans('cruds.process.title') }} :
                                    <select name="process" onchange="this.form.submit()">
                                        <option value="">-- All --</option>
                                        @if ($all_process!=null)
                                            @foreach ($all_process as $process)
                                                <option value="{{$process->id}}" {{ Session::get('process')==$process->id ? "selected" : "" }}>{{ $process->name }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                @endif
                <div class="graphviz" id="graph"></div>
            </div>
        </div>
        @can('macro_processus_access')
        @if ($macroProcessuses->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans('cruds.macroProcessus.title') }} :
            </div>
            <div class="card-body">
                <p>{{ trans('cruds.macroProcessus.description') }}</p>
                  @foreach($macroProcessuses as $macroProcess)
                  <div class="row">
                    <div class="col-sm-6">
                    <table class="table table-bordered table-striped table-hover">
                        <thead id="MACROPROCESS{{ $macroProcess->id }}">
                            <th colspan="2">
                                <a href="/admin/macro-processuses/{{ $macroProcess->id }}">{{ $macroProcess->name }}</a>
                            </th>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="20%"><b>{{ trans('cruds.macroProcessus.fields.description') }}</b></td>
                                <td>{!! $macroProcess->description !!}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans('cruds.macroProcessus.fields.io_elements') }}</b></td>
                                <td>{!! $macroProcess->io_elements !!}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans('cruds.macroProcessus.fields.security_need') }}</b></td>
                                <td>
                                    {{ trans('global.confidentiality_short') }} :
                                        @if ($macroProcess->security_need_c==0){{ trans('global.none') }}@endif
                                        @if ($macroProcess->security_need_c==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($macroProcess->security_need_c==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($macroProcess->security_need_c==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($macroProcess->security_need_c==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.integrity_short') }} :
                                        @if ($macroProcess->security_need_i==0){{ trans('global.none') }}@endif
                                        @if ($macroProcess->security_need_i==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($macroProcess->security_need_i==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($macroProcess->security_need_i==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($macroProcess->security_need_i==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.availability_short') }} :
                                        @if ($macroProcess->security_need_a==0){{ trans('global.none') }}@endif
                                        @if ($macroProcess->security_need_a==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($macroProcess->security_need_a==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($macroProcess->security_need_a==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($macroProcess->security_need_a==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.tracability_short') }} :
                                        @if ($macroProcess->security_need_t==0){{ trans('global.none') }}@endif
                                        @if ($macroProcess->security_need_t==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($macroProcess->security_need_t==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($macroProcess->security_need_t==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($macroProcess->security_need_t==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    @if (config('mercator-config.parameters.security_need_auth'))
                                    &nbsp;
                                    {{ trans('global.authenticity_short') }} :
                                        @if ($macroProcess->security_need_auth==0){{ trans('global.none') }}@endif
                                        @if ($macroProcess->security_need_auth==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($macroProcess->security_need_auth==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($macroProcess->security_need_auth==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($macroProcess->security_need_auth==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td><b>{{ trans('cruds.macroProcessus.fields.owner') }}</b></td>
                                <td>{{ $macroProcess->owner }}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans('cruds.macroProcessus.fields.processes') }}</b></td>
                                <td>
                                @foreach($macroProcess->processes as $process)
                                    <a href="#PROCESS{{ $process->id }}">{{ $process->name }}</a>
                                    @if (!$loop->last)
                                    ,
                                    @endif
                                @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @endif
        @endcan
        @can('process_access')
        @if ($processes->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans('cruds.process.title') }}
            </div>
            <div class="card-body">
                <p>{{ trans('cruds.process.description') }}</p>
                    @foreach($processes as $process)
                      <div class="row">
                        <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="PROCESS{{ $process->id }}">
                                <th colspan="2">
                                    <a href="/admin/processes/{{ $process->id }}">{{ $process->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>{{ trans('cruds.process.fields.description') }}</b></td>
                                    <td>{!! $process->description !!}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.process.fields.in_out') }}</b></td>
                                    <td>{!! $process->in_out !!}</td>
                                </tr>
                                @if (auth()->user()->granularity>=3)
                                <tr>
                                    <td><b>{{ trans('cruds.process.fields.activities') }}</b></td>
                                    <td>
                                        @foreach($process->activities as $activity)
                                            <a href="#ACTIVITY{{ $activity->id }}">{{ $activity->name }}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                <tr>
                                    <td><b>{{ trans('cruds.process.fields.entities') }}</b></td>
                                    <td>
                                        @foreach($process->entities as $entity)
                                            <a href="/admin/report/ecosystem#ENTITY{{$entity->id}}">{{$entity->name}}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.process.fields.applications') }}</b></td>
                                    <td>
                                        @foreach($process->applications as $application)
                                            <a href="/admin/report/applications#APPLICATION{{$application->id}}">{{$application->name}}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.process.fields.security_need') }}</b></td>
                                    <td>
                                    {{ trans('global.confidentiality_short') }} :
                                        @if ($process->security_need_c==0){{ trans('global.none') }}@endif
                                        @if ($process->security_need_c==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($process->security_need_c==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($process->security_need_c==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($process->security_need_c==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.integrity_short') }} :
                                        @if ($process->security_need_i==0){{ trans('global.none') }}@endif
                                        @if ($process->security_need_i==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($process->security_need_i==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($process->security_need_i==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($process->security_need_i==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.availability_short') }} :
                                        @if ($process->security_need_a==0){{ trans('global.none') }}@endif
                                        @if ($process->security_need_a==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($process->security_need_a==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($process->security_need_a==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($process->security_need_a==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.tracability_short') }} :
                                        @if ($process->security_need_t==0){{ trans('global.none') }}@endif
                                        @if ($process->security_need_t==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($process->security_need_t==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($process->security_need_t==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($process->security_need_t==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    @if (config('mercator-config.parameters.security_need_auth'))
                                    &nbsp;
                                    {{ trans('global.authenticity_short') }} :
                                        @if ($process->security_need_auth==0){{ trans('global.none') }}@endif
                                        @if ($process->security_need_auth==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($process->security_need_auth==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($process->security_need_auth==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($process->security_need_auth==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.process.fields.owner') }}</b></td>
                                    <td>{{ $process->owner }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                 </div>
                    @endforeach
            </div>
        </div>
        @endif
        @endcan

        @can('activity_access')
        @if ($activities->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans('cruds.activity.title') }}
            </div>
            <div class="card-body">
                <p>{{ trans('cruds.activity.description') }}</p>
                    @foreach($activities as $activity)
                  <div class="row">
                    <div class="col-sm-6">
                    <table class="table table-bordered table-striped table-hover">
                        <thead id="ACTIVITY{{ $activity->id }}">
                            <th colspan="2">
                                <a href="/admin/activities/{{ $activity->id }}">{{ $activity->name }}</a>
                            </th>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="20%"><b>{{ trans('cruds.activity.fields.description') }}</b></td>
                                <td>{!! $activity->description !!}</td>
                            </tr>
                            <tr>
                                <td>
                                    <b>{{ trans('cruds.activity.fields.processes') }}</b>
                                </td>
                                <td>
                                    @foreach($activity->processes as $process)
                                        <a href="#PROCESS{{ $process->id }}">{{ $process->name }}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <b>{{ trans('cruds.activity.fields.operations') }}</b>
                                </td>
                                <td>
                                    @foreach($activity->operations as $operation)
                                        <a href="#OPERATION{{ $operation->id }}">{{ $operation->name }}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @endif
        @endcan

        @can('operation_access')
        @if ($operations->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans('cruds.operation.title') }}
            </div>
            <div class="card-body">
                <p>{{ trans('cruds.operation.description') }}</p>
                    @foreach($operations as $operation)
                  <div class="row">
                    <div class="col-sm-6">
                    <table class="table table-bordered table-striped table-hover">
                        <thead id="OPERATION{{ $operation->id }}">
                            <th colspan="2">
                                <a href="/admin/operations/{{ $operation->id }}">{{ $operation->name }}</a>
                            </th>
                        </thead>
                        <tbody>
                            <tr>
                                <td width="20%"><b>{{ trans('cruds.operation.fields.description') }}</b></td>
                                <td>{!! $operation->description !!}</td>
                            </tr>
                            <tr>
                                <td><b>{{ trans('cruds.operation.fields.process') }}</b></td>
                                <td>
                                    @if($operation->process!=null)
                                        <a href="#PROCESS{{$operation->process->id}}">{{ $operation->process->name }}</a>
                                    @endif
                                </td>
                            </tr>
                            @if (auth()->user()->granularity>=3)
                            <tr>
                                <td>
                                    <b>{{ trans('cruds.operation.fields.activities') }}</b>
                                </td>
                                <td>
                                    @foreach($operation->activities as $activity)
                                        <a href="{{ route('admin.activities.show', $activity->id) }}">
                                            {{ $activity->name }}
                                        </a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td>
                                    <b>{{ trans('cruds.operation.fields.tasks') }}</b>
                                </td>
                                <td>
                                    @foreach($operation->tasks as $task)
                                        <a href="#TASK{{$task->id}}">{{$task->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                            <tr>
                                <td><b>{{ trans('cruds.operation.fields.actors') }}</b></td>
                                <td>
                                    @foreach($operation->actors as $actor)
                                        <a href="#ACTOR{{$actor->id}}">{{$actor->name}}</a>
                                        @if (!$loop->last)
                                        ,
                                        @endif
                                    @endforeach
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            @endforeach
            </div>
        </div>
        @endif
        @endcan

        @can('task_access')
        @if ($tasks->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans('cruds.task.title') }}
            </div>
            <div class="card-body">
                <p>{{ trans('cruds.task.description') }}</p>
                    @foreach($tasks as $task)
                      <div class="row">
                        <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="TASK{{ $task->id }}">
                                <th colspan="2">
                                    <a href="/admin/tasks/{{ $task->id }}">{{ $task->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>{{ trans('cruds.task.fields.description') }}</b></td>
                                    <td>{!! $task->description !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endcan

        @can('actors_access')
        @if ($actors->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans('cruds.actor.title') }}
            </div>
            <div class="card-body">
                <p>{{ trans('cruds.actor.description') }}</p>
                    @foreach($actors as $actor)
                      <div class="row">
                        <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="ACTOR{{ $actor->id }}">
                                <th colspan="2">
                                    <a href="/admin/actors/{{ $actor->id }}">{{ $actor->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>{{ trans('cruds.actor.fields.contact') }}</b></td>
                                    <td>{{ $actor->contact }}</td>
                                </tr>
                                <tr>
                                    <td width="20%"><b>{{ trans('cruds.actor.fields.nature') }}</b></td>
                                    <td>{{ $actor->nature }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.actor.fields.type') }}</b></td>
                                    <td>{{ $actor->type }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endcan

        @can('information_access')
        @if ($informations->count()>0)
        <br>
        <div class="card">
            <div class="card-header">
                {{ trans('cruds.information.title') }}
            </div>
            <div class="card-body">
                <p>{{ trans('cruds.information.description') }}</p>
                    @foreach($informations as $information)
                      <div class="row">
                        <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="INFORMATION{{ $information->id }}">
                                <th colspan="2">
                                    <a href="/admin/information/{{ $information->id }}">{{ $information->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>{{ trans('cruds.information.fields.description') }}</b></td>
                                    <td>{!! $information->description !!}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.information.fields.owner') }}</b></td>
                                    <td>{{ $information->owner }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.information.fields.administrator') }}</b></td>
                                    <td>{{ $information->administrator }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.information.fields.storage') }}</b></td>
                                    <td>{{ $information->storage }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.information.fields.processes') }}</b></td>
                                    <td>
                                        @foreach($information->processes as $process)
                                            <a href="#PROCESS{{ $process->id}}">{{ $process->name }}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.information.fields.security_need') }}</b></td>
                                    <td>
                                    {{ trans('global.confidentiality_short') }} :
                                        @if ($information->security_need_c==0){{ trans('global.none') }}@endif
                                        @if ($information->security_need_c==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($information->security_need_c==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($information->security_need_c==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($information->security_need_c==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.integrity_short') }} :
                                        @if ($information->security_need_i==0){{ trans('global.none') }}@endif
                                        @if ($information->security_need_i==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($information->security_need_i==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($information->security_need_i==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($information->security_need_i==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.availability_short') }} :
                                        @if ($information->security_need_a==0){{ trans('global.none') }}@endif
                                        @if ($information->security_need_a==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($information->security_need_a==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($information->security_need_a==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($information->security_need_a==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    &nbsp;
                                    {{ trans('global.tracability_short') }} :
                                        @if ($information->security_need_t==0){{ trans('global.none') }}@endif
                                        @if ($information->security_need_t==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($information->security_need_t==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($information->security_need_t==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($information->security_need_t==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    @if (config('mercator-config.parameters.security_need_auth'))
                                    &nbsp;
                                    {{ trans('global.authenticity_short') }} :
                                        @if ($information->security_need_auth==0){{ trans('global.none') }}@endif
                                        @if ($information->security_need_auth==1)<span class="veryLowRisk">{{ trans('global.low') }}</span>@endif
                                        @if ($information->security_need_auth==2)<span class="lowRisk">{{ trans('global.medium') }}</span>@endif
                                        @if ($information->security_need_auth==3)<span class="mediumRisk">{{ trans('global.strong') }}</span>@endif
                                        @if ($information->security_need_auth==4)<span class="highRisk">{{ trans('global.very_strong') }}</span>@endif
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.information.fields.sensitivity') }}</b></td>
                                    <td>{{ $information->sensitivity }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.information.fields.constraints') }}</b></td>
                                    <td>{!! $information->constraints !!}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        @endcan
    </div>
</div>
@endsection

@section('scripts')
@vite(['resources/js/d3-viz.js'])
<script>
let dotSrc=`
digraph  {
    @if (auth()->user()->granularity>=2)
    @foreach($macroProcessuses as $macroProcess)
        MP{{ $macroProcess->id }} [label="{{ $macroProcess->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/macroprocess.png"  href="#MACROPROCESS{{ $macroProcess->id }}"]
    @endforeach
    @endif
    @foreach($processes as $process)
        P{{ $process->id }} [label="{{ $process->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/process.png"  href="#PROCESS{{ $process->id }}"]
        @if(auth()->user()->granularity==3)
            @foreach($process->activities as $activity)
                P{{$process->id}} -> A{{$activity->id}}
            @endforeach
        @endif
        @foreach($process->information as $information)
            P{{ $process->id }} -> I{{ $information->id }}
        @endforeach
        @if (auth()->user()->granularity>=2)
            @if ($process->macroprocess_id!=null)
                MP{{ $process->macroprocess_id }} -> P{{$process->id}}
            @endif
        @endif
        @if (auth()->user()->granularity<=2)
            @foreach($process->operations as $operation)
                P{{ $process->id }} -> O{{ $operation->id }}
            @endforeach
        @endif
    @endforeach
    @if (auth()->user()->granularity>=3)
    @foreach($activities as $activity)
        A{{ $activity->id }} [label="{{ $activity->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/activity.png"  href="#ACTIVITY{{ $activity->id }}"]
        @foreach($activity->operations as $operation)
            A{{ $activity->id }} -> O{{ $operation->id }}
        @endforeach
        @if($activity->process!=null)
            A{{ $activity->id }} -> P{{ $operation->process->id }}
        @endif
    @endforeach
    @endif
    @foreach($operations as $operation)
        O{{ $operation->id }} [label="{{ $operation->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/operation.png"  href="#OPERATION{{ $operation->id }}"]
        @if (auth()->user()->granularity==3)
        @foreach($operation->tasks as $task)
            O{{ $operation->id }} -> T{{ $task->id }}
        @endforeach
        @endif
        @if (auth()->user()->granularity>=2)
        @foreach($operation->actors as $actor)
            O{{ $operation->id }} -> ACT{{ $actor->id }}
        @endforeach
        @endif
    @endforeach
    @if (auth()->user()->granularity==3)
    @foreach($tasks as $task)
        T{{ $task->id }} [label="{{ $task->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/task.png"  href="#TASK{{ $task->id }}"]
    @endforeach
    @endif
    @if (auth()->user()->granularity>=2)
    @foreach($actors as $actor)
        ACT{{ $actor->id }} [label="{{ $actor->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/actor.png"  href="#ACTOR{{ $actor->id }}"]
    @endforeach
    @endif
    @foreach($informations as $information)
        I{{ $information->id }} [label="{{ $information->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/information.png"  href="#INFORMATION{{ $information->id }}"]
    @endforeach
}`;

document.addEventListener('DOMContentLoaded', () => {
d3.select("#graph").graphviz()
    .addImage("/images/macroprocess.png", "64px", "64px")
    .addImage("/images/process.png", "64px", "64px")
    .addImage("/images/activity.png", "64px", "64px")
    .addImage("/images/operation.png", "64px", "64px")
    .addImage("/images/task.png", "64px", "64px")
    .addImage("/images/actor.png", "64px", "64px")
    .addImage("/images/information.png", "64px", "64px")
        .renderDot(dotSrc);
});
</script>
@parent
@endsection
