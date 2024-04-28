@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.menu.application.title") }}
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (auth()->user()->granularity>=2)
                    <div class="col-sm-5">
                        <form action="/admin/report/applications">
                                <table class="table table-bordered table-striped">
                                    <tr>
                                        <td>
                                            {{ trans("cruds.applicationBlock.title") }} :
                                            <select name="applicationBlock" onchange="this.form.application.value='';this.form.submit()">
                                                <option value="">-- All --</option>
                                                @foreach ($all_applicationBlocks as $applicationBlock)
                                                    <option value="{{$applicationBlock->id}}" {{ Session::get('applicationBlock')==$applicationBlock->id ? "selected" : "" }}>{{ $applicationBlock->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            {{ trans("cruds.application.title") }} :
                                            <select name="application" onchange="this.form.submit()">
                                                <option value="">-- All --</option>
                                                @if ($all_applications!=null)
                                                    @foreach ($all_applications as $application)
                                                        <option value="{{$application->id}}" {{ Session::get('application')==$application->id ? "selected" : "" }}>{{ $application->name }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </div>
                    @endif

                    <div id="graph"></div>

                </div>
            </div>

            @can('application_block_access')
            @if ((auth()->user()->granularity>=2)&&($applicationBlocks->count()>0))
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.applicationBlock.title") }}
                </div>

                <div class="card-body">
                    <p>{{ trans("cruds.applicationBlock.description") }}</p>

                      @foreach($applicationBlocks as $ab)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="APPLICATIONBLOCK{{ $ab->id }}">
                                    <th colspan="2">
                                    <a href="/admin/application-blocks/{{ $ab->id }}">{{ $ab->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">{{ trans("cruds.applicationBlock.fields.description") }}</th>
                                        <td>{!! $ab->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.applicationBlock.fields.responsible_helper") }}</th>
                                        <td>{{ $ab->responsible }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.applicationBlock.fields.applications_helper") }}</th>
                                        <td>
                                            @foreach($ab->applications as $application)
                                                <a href="#APPLICATION{{$application->id}}"> {{ $application->name }}</a>
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

            @can('application_access')
            @if ($applications->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.application.title") }}
                </div>

                <div class="card-body">
                    <p>{{ trans("cruds.application.description") }}</p>
                     @foreach($applications as $application)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="APPLICATION{{$application->id}}">
                                    <th colspan="2">
                                        <a href="/admin/applications/{{ $application->id }}">{{ $application->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">{{ trans("cruds.application.fields.description") }}</th>
                                        <td>{!! $application->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.version") }}</th>
                                        <td>{{ $application->version }}</td>
                                    </tr>
                                    @if (auth()->user()->granularity>=2)
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.entities_helper") }}</th>
                                        <td>
                                            @foreach($application->entities as $entity)
                                                <a href="/admin/report/ecosystem#ENTITY{{$entity->id}}">{{ $entity->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.entity_resp_helper") }}</th>
                                        <td>
                                            @if (isset($application->entity_resp))
                                                <a href="/admin/report/ecosystem#ENTITY{{$application->entity_resp->id}}">{{  $application->entity_resp->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.responsible") }}</th>
                                        <td>{{ $application->responsible}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.technology") }}</th>
                                        <td>{{ $application->technology}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.type") }}</th>
                                        <td>{{ $application->type}}</td>
                                    </tr>
                                    @if (auth()->user()->granularity>=2)
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.users") }}</th>
                                        <td>{{ $application->users}}</td>
                                    </tr>
                                    @endif
                                    @if (auth()->user()->granularity>=2)
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.documentation_helper") }}</th>
                                        <td><a href="{{ $application->documentation}}">{{ $application->documentation}}</a></td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.flux_helper") }}</th>
                                        <td>
                                            {{ trans("cruds.flux.fields.source") }} :
                                            @foreach($application->applicationSourceFluxes as $flux)
                                                <a href="#FLUX{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                            <br>{{ trans("cruds.flux.fields.destination") }} :
                                            @foreach($application->applicationDestFluxes as $flux)
                                                <a href="#FLUX{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.security_need") }}</th>
                                        <td>
                                            {{ trans('global.confidentiality') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$application->security_need_c] ?? "" }}
                                            <br>
                                            {{ trans('global.integrity') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$application->security_need_i] ?? "" }}
                                            <br>
                                            {{ trans('global.availability') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$application->security_need_a] ?? "" }}
                                            <br>
                                            {{ trans('global.tracability') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$application->security_need_t] ?? "" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.RTO") }}</th>
                                        <td>
                                            @if (intdiv($application->rto,60 * 24) > 0)
                                                {{ intdiv($application->rto,60 * 24) }}
                                                @if (intdiv($application->rto,60 * 24) > 1)
                                                    {{ trans('global.days') }}
                                                @else
                                                    {{ trans('global.day') }}
                                                @endif
                                            @endif
                                            @if ((intdiv($application->rto,60) % 24) > 0)
                                                {{ intdiv($application->rto,60) % 24 }}
                                                @if ((intdiv($application->rto,60) % 24) > 1)
                                                    {{ trans('global.hours') }}
                                                @else
                                                    {{ trans('global.hour') }}
                                                @endif
                                            @endif
                                            @if (($application->rto % 60) > 0)
                                                {{ $application->rto % 60 }}
                                                @if (($application->rto % 60) > 1)
                                                    {{ trans('global.minutes') }}
                                                @else
                                                    {{ trans('global.minute') }}
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.RPO") }}</th>
                                        <td>
                                            {{ intdiv($application->rpo,60 * 24) }}
                                            @if (intdiv($application->rpo,60 * 24) > 0)
                                                @if (intdiv($application->rpo,60 * 24) > 1)
                                                    {{ trans('global.days') }}
                                                @else
                                                    {{ trans('global.day') }}
                                                @endif
                                            @endif
                                            @if ((intdiv($application->rpo,60) % 24) > 0)
                                                {{ intdiv($application->rpo,60) % 24 }}
                                                @if ((intdiv($application->rpo,60) % 24) > 1)
                                                    {{ trans('global.hours') }}
                                                @else
                                                    {{ trans('global.hour') }}
                                                @endif
                                            @endif
                                            @if (($application->rpo % (24*60)) > 0)
                                                {{ $application->rpo % (24*60) }}
                                                @if (($application->rpo % (24*60)) > 1)
                                                    {{ trans('global.minutes') }}
                                                @else
                                                    {{ trans('global.minute') }}
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.external") }}</th>
                                        <td>{{ $application->external}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.processes_helper") }}</th>
                                        <td>
                                            @foreach($application->processes as $process)
                                                <a href="/admin/report/information_system#PROCESS{{$process->id}}">{{ $process->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    @if (auth()->user()->granularity>=2)
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.services_helper") }}</th>
                                        <td>
                                            @foreach($application->services as $service)
                                                <a href="#SERVICE{{$service->id}}">{{ $service->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.databases_helper") }}</th>
                                        <td>
                                            @foreach($application->databases as $database)
                                                <a href="#DATABASE{{$database->id}}">{{ $database->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.application_block_helper") }}</th>
                                        <td>
                                            @if ($application->application_block!=null)
                                            <a href="#APPLICATIONBLOCK{{$application->application_block_id}}">{{$application->application_block->name }}</a>
                                            @endif
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.application.fields.logical_servers_helper") }}</th>
                                        <td>
                                            @foreach($application->logical_servers as $logical_server)
                                                <a href="/admin/report/logical_infrastructure#LOGICAL_SERVER{{$logical_server->id}}">{{ $logical_server->name }}</a>
                                                @if(!$loop->last)
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

            @can('application_service_access')
            @if ((auth()->user()->granularity>=2)&&($applicationServices->count()>0))
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.applicationService.title") }}
                </div>

                <div class="card-body">
                    <p>{{ trans("cruds.applicationService.description") }}</p>
                      @foreach($applicationServices as $applicationService)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="APPLICATIONSERVICE{{$applicationService->id}}">
                                    <th colspan="2">
                                        <a href="/admin/application-services/{{ $applicationService->id }}">{{ $applicationService->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">{{ trans("cruds.applicationService.fields.description") }}</th>
                                        <td>{!! $applicationService->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.applicationService.fields.modules_helper") }}</th>
                                        <td>
                                            @foreach($applicationService->modules as $module)
                                                <a href="#APPLICATIONMODULE{{ $module->id }}">{{ $module->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.applicationService.fields.flux_helper") }}</th>
                                        <td>
                                            {{ trans("cruds.flux.fields.source") }} :
                                            @foreach($applicationService->serviceSourceFluxes as $flux)
                                                <a href="#FLUX{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                            <br>{{ trans("cruds.flux.fields.destination") }} :
                                            @foreach($applicationService->serviceDestFluxes as $flux)
                                                <a href="#FLUX{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.applicationService.fields.exposition_helper") }}</th>
                                        <td>{{ $applicationService->exposition }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.applicationService.fields.applications_helper") }}</th>
                                        <td>
                                            @foreach($applicationService->applications as $application)
                                                <a href="#APPLICATION{{ $application->id }}">{{ $application->name }}</a>
                                                @if(!$loop->last)
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

            @can('application_module_access')
            @if ((auth()->user()->granularity>=2)&&($applicationModules->count()>0))
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.applicationModule.title") }}
                </div>

                <div class="card-body">
                    <p>{{ trans("cruds.applicationModule.description") }}</p>
                        @foreach($applicationModules as $applicationModule)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="APPLICATIONMODULE{{$applicationModule->id}}">
                                        <th colspan="2">
                                            <a href="/admin/application-modules/{{ $applicationModule->id }}">{{ $applicationModule->name }}</a><br>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="30%">{{ trans("cruds.applicationModule.fields.description") }}</p></th>
                                            <td>{!! $applicationModule->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans("cruds.applicationModule.fields.services_helper") }}</th>
                                            <td>
                                                @foreach($applicationModule->applicationServices as $service)
                                                    <a href="#APPLICATIONSERVICE{{ $service->id }}">{{ $service->name }}</a>
                                                    @if(!$loop->last)
                                                    ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans("cruds.applicationModule.fields.flux_helper") }}</th>
                                            <td>
                                                {{ trans("cruds.flux.fields.source") }} :
                                                @foreach($applicationModule->moduleSourceFluxes as $flux)
                                                    <a href="#FLUX{{$flux->id}}">{{ $flux->name }}</a>
                                                    @if (!$loop->last)
                                                    ,
                                                    @endif
                                                @endforeach
                                                <br>{{ trans("cruds.flux.fields.destination") }} :
                                                @foreach($applicationModule->moduleDestFluxes as $flux)
                                                    <a href="#FLUX{{$flux->id}}">{{ $flux->name }}</a>
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

            @can('database_access')
            @if ($databases->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.database.title") }}
                </div>

                <div class="card-body">
                    <p>{{ trans("cruds.database.description") }}</p>
                    @foreach($databases as $database)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="DATABASE{{$database->id}}">
                                    <th colspan="2">
                                        <a href="/admin/databases/{{ $database->id }}">{{ $database->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">{{ trans("cruds.database.fields.description") }}</th>
                                        <td>{!! $database->description !!}</td>
                                    </tr>
                                    @if (auth()->user()->granularity>=2)
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.entities") }}</th>
                                        <td>
                                            @foreach($database->entities as $entity)
                                                <a href="/admin/report/ecosystem#ENTITY{{ $entity->id }}">{{ $entity->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.entity_resp") }}</th>
                                        <td>
                                            @if ($database->entity_resp!=null)
                                            <a href="/admin/report/ecosystem#ENTITY{{ $database->entity_resp->id }}">{{ $database->entity_resp->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    @endif
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.responsible") }}</th>
                                        <td>{{ $database->responsible }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.type") }}</th>
                                        <td>{{ $database->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.flux_helper") }}</th>
                                        <td>
                                            {{ trans("cruds.flux.fields.source") }} :
                                            @foreach($database->databaseSourceFluxes as $flux)
                                                <a href="#FLUX{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                            <br>{{ trans("cruds.flux.fields.destination") }} :
                                            @foreach($database->databaseDestFluxes as $flux)
                                                <a href="#FLUX{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.informations_helper") }}</th>
                                        <td>
                                            @foreach($database->informations as $information)
                                                <a href="/admin/report/information_system#INFORMATION{{ $information->id }}">{{ $information->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.applications_helper") }}</th>
                                        <td>
                                            @foreach($database->applications as $application)
                                                <a href="/admin/report/applications#APPLICATION{{ $application->id }}">{{ $application->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>
                                            {{ trans('cruds.database.fields.logical_servers') }}
                                        </th>
                                        <td>
                                            @foreach($database->logicalServers as $logicalServer)
                                                <a href="/admin/report/logical_infrastructure#LOGICAL_SERVER{{ $logicalServer->id }}">
                                                    {{ $logicalServer->name }}
                                                </a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.security_need") }}</th>
                                        <td>
                                            {{ trans('global.confidentiality') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$database->security_need_c] ?? "" }}
                                            <br>
                                            {{ trans('global.integrity') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$database->security_need_i] ?? "" }}
                                            <br>
                                            {{ trans('global.availability') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$database->security_need_a] ?? "" }}
                                            <br>
                                            {{ trans('global.tracability') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$database->security_need_t] ?? "" }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans("cruds.database.fields.external") }}</th>
                                        <td>{{ $database->external }}</td>
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

            @can('flux_access')
            @if ($fluxes->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.flux.title") }}
                </div>

                <div class="card-body">
                    <p>{{ trans("cruds.flux.description") }}</p>
                    @foreach($fluxes as $flux)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="FLUX{{$flux->id}}">
                                    <th colspan="2">
                                        <a href="/admin/fluxes/{{ $flux->id }}">{{ $flux->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">{{ trans("cruds.flux.fields.description") }}</th>
                                        <td>{!! $flux->description !!}</td>
                                    </tr>
                                    @if ($flux->application_source!=null)
                                    <tr>
                                        <th>{{ trans("cruds.flux.fields.application_source") }}</th>
                                        <td><a href="#APPLICATION{{$flux->application_source->id}}">{{$flux->application_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->service_source!=null)
                                    <tr>
                                        <th>{{ trans("cruds.flux.fields.service_source") }}</th>
                                        <td><a href="#SERVICE{{$flux->service_source->id}}">{{$flux->service_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->module_source!=null)
                                    <tr>
                                        <th>{{ trans("cruds.flux.fields.module_source") }}</th>
                                        <td><a href="#MODULE{{$flux->module_source->id}}">{{$flux->module_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->database_source!=null)
                                    <tr>
                                        <th>{{ trans("cruds.flux.fields.database_source") }}</th>
                                        <td><a href="#DATABASE{{$flux->database_source->id}}">{{$flux->database_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->application_dest!=null)
                                    <tr>
                                        <th>{{ trans("cruds.flux.fields.application_dest") }}</th>
                                        <td><a href="#APPLICATION{{$flux->application_dest->id}}">{{$flux->application_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->service_dest!=null)
                                    <tr>
                                        <th>{{ trans("cruds.flux.fields.service_dest") }}</th>
                                        <td><a href="#SERVICE{{$flux->service_dest->id}}">{{$flux->service_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->module_dest!=null)
                                    <tr>
                                        <th>{{ trans("cruds.flux.fields.module_dest") }}</th>
                                        <td><a href="#MODULE{{$flux->module_dest->id}}">{{$flux->module_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->database_dest!=null)
                                    <tr>
                                        <th>{{ trans("cruds.flux.fields.database_dest") }}</th>
                                        <td><a href="#DATABASE{{$flux->database_dest->id}}">{{$flux->database_dest->name}}</a></td>
                                    </tr>
                                    @endif
                                </li>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    @endcan
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<!-- //d3js.org/d3.v5.min.js -->
<script src="/js/d3.v5.min.js"></script>
<!-- https://unpkg.com/@hpcc-js/wasm@0.3.11/dist/index.min.js -->
<script src="/js/index.min.js"></script>
<!-- https://unpkg.com/d3-graphviz@3.0.5/build/d3-graphviz.js -->
<script src="/js/d3-graphviz.js"></script>

<script>
let dotSrc=`
digraph  {
    @can('application_block_access')
    @if (auth()->user()->granularity>=2)
        @foreach($applicationBlocks as $ab)
            AB{{ $ab->id }} [label="{{ $ab->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/applicationblock.png" href="#APPLICATIONBLOCK{{$ab->id}}"]
        @endforEach
    @endif
    @endcan
    @can('application_access')
    @foreach($applications as $application)
        A{{ $application->id }} [label="{{ $application->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/application.png" href="#APPLICATION{{$application->id}}"]
        @if (auth()->user()->granularity>=2)
            @foreach($application->services as $service)
                A{{ $application->id }} -> AS{{ $service->id}}
            @endforeach
        @endif
        @foreach($application->databases as $database)
            A{{ $application->id }} -> DB{{ $database->id}}
        @endforeach
        @if (auth()->user()->granularity>=2)
            @if ($application->application_block_id!=null)
                AB{{ $application->application_block_id }} -> A{{ $application->id}}
            @endif
        @endif
    @endforEach
    @endcan
    @can('application_service_access')
    @if (auth()->user()->granularity>=2)
        @foreach($applicationServices as $service)
            AS{{ $service->id }} [label="{{ $service->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/applicationservice.png" href="#APPLICATIONSERVICE{{$service->id}}"]
            @foreach($service->modules as $module)
                AS{{ $service->id }} -> M{{$module->id}}
            @endforeach
        @endforeach
        @foreach($applicationModules as $module)
            M{{ $module->id }} [label="{{ $module->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/applicationmodule.png" href="#APPLICATIONMODULE{{$module->id}}"]
        @endforeach
    @endif
    @endcan
    @can('database_access')
    @foreach($databases as $database)
        DB{{ $database->id }} [label="{{ $database->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/database.png" href="#DATABASE{{$database->id}}"]
    @endforeach
    @endcan
}`;

d3.select("#graph").graphviz()
    .addImage("/images/applicationblock.png", "64px", "64px")
    .addImage("/images/application.png", "64px", "64px")
    .addImage("/images/applicationservice.png", "64px", "64px")
    .addImage("/images/applicationmodule.png", "64px", "64px")
    .addImage("/images/database.png", "64px", "64px")
    .renderDot(dotSrc);

</script>
@parent
@endsection
