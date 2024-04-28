@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.menu.application_flow.title') }}
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                <div class="col-sm-8">
                    <form action="/admin/report/application_flows">
                        <table class="table table-bordered table-striped">
                            <tr>
                                <td>
                                    {{ trans('cruds.applicationBlock.title') }}
                                    <div style="padding-bottom: 4px">
                                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                    </div>
                                    <select class="form-control select2 " name="applicationBlocks[]" id="applicationBlocks" multiple onchange="this.form.submit();">
                                        @foreach($all_applicationBlocks as $id => $name)
                                            <option value="{{ $id }}" {{ in_array($id, Session::get('applicationBlocks')) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{ trans('cruds.application.title') }}
                                    <div style="padding-bottom: 4px">
                                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                    </div>
                                    <select class="form-control select2 " name="applications[]" id="applications" multiple onchange="this.form.submit();">
                                        @foreach($all_applications as $id => $name)
                                            <option value="{{ $id }}" {{ in_array($id, Session::get('applications')) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    {{ trans('cruds.database.title') }}
                                    <div style="padding-bottom: 4px">
                                        <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                                        <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                                    </div>
                                    <select class="form-control select2 " name="databases[]" id="databases" multiple onchange="this.form.submit();">
                                        @foreach($all_databases as $id => $name)
                                            <option value="{{ $id }}" {{ in_array($id, Session::get('databases')) ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>
                <div id="graph"></div>
                </div>
            </div>

            @can('flux_access')
            @if($flows->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.flux.title') }}
                </div>

                <div class="card-body">
                    <p>{{ trans('cruds.flux.description') }}</p>
                    @foreach($flows as $flux)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="FLOW{{$flux->id}}">
                                    <th colspan="2">
                                        <a href="/admin/fluxes/{{ $flux->id }}">{{ $flux->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">{{ trans('cruds.flux.fields.description') }}</th>
                                        <td>{!! $flux->description !!}</td>
                                    </tr>
                                    @if ($flux->application_source!=null)
                                    <tr>
                                        <th>{{ trans('cruds.flux.fields.application_source') }}</th>
                                        <td><a href="#APPLICATION{{$flux->application_source->id}}">{{$flux->application_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->service_source!=null)
                                    <tr>
                                        <th>{{ trans('cruds.flux.fields.service_source') }}</th>
                                        <td><a href="#SERVICE{{$flux->service_source->id}}">{{$flux->service_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->module_source!=null)
                                    <tr>
                                        <th>{{ trans('cruds.flux.fields.module_source') }}</th>
                                        <td><a href="#MODULE{{$flux->module_source->id}}">{{$flux->module_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->database_source!=null)
                                    <tr>
                                        <th>{{ trans('cruds.flux.fields.database_source') }}</th>
                                        <td><a href="#DATABASE{{$flux->database_source->id}}">{{$flux->database_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->application_dest!=null)
                                    <tr>
                                        <th>{{ trans('cruds.flux.fields.application_dest') }}</th>
                                        <td><a href="#APPLICATION{{$flux->application_dest->id}}">{{$flux->application_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->service_dest!=null)
                                    <tr>
                                        <th>{{ trans('cruds.flux.fields.service_dest') }}</th>
                                        <td><a href="#SERVICE{{$flux->service_dest->id}}">{{$flux->service_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->module_dest!=null)
                                    <tr>
                                        <th>{{ trans('cruds.flux.fields.module_dest') }}</th>
                                        <td><a href="#MODULE{{$flux->module_dest->id}}">{{$flux->module_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->database_dest!=null)
                                    <tr>
                                        <th>{{ trans('cruds.flux.fields.database_dest') }}</th>
                                        <td><a href="#DATABASE{{$flux->database_dest->id}}">{{$flux->database_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->crypted || $flux->bidirectional)
                                    <tr>
                                        <td colspan="2">
                                            @if ($flux->crypted)
                                            {{ trans('cruds.flux.fields.crypted_helper') }}
                                            @endif
                                            @if ($flux->bidirectional)
                                            {{ trans('cruds.flux.fields.bidirectional_helper') }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endif

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
            @if($applications->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.application.title') }}
                </div>

                <div class="card-body">
                    <p>{{ trans('cruds.application.description') }}</p>
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
                                        <th width="30%">{{ trans('cruds.application.fields.description') }}</th>
                                        <td>{!! $application->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.entities') }}</th>
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
                                        <th>{{ trans('cruds.application.fields.entity_resp') }}</th>
                                        <td>
                                            @if (isset($application->entity_resp))
                                                <a href="/admin/report/ecosystem#ENTITY{{$application->entity_resp->id}}">{{  $application->entity_resp->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.responsible') }}</th>
                                        <td>{{ $application->responsible}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.technology') }}</th>
                                        <td> {{ $application->technology}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.type') }}</th>
                                        <td> {{ $application->type}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.users') }}</th>
                                        <td> {{ $application->users}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.documentation') }}</th>
                                        <td><a href="{{ $application->documentation}}">{{ $application->documentation}}</a></td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.flux') }}</th>
                                        <td>
                                            {{ trans('cruds.flux.fields.source') }} :
                                            @foreach($application->applicationSourceFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                            <br>
                                            {{ trans('cruds.flux.fields.destination') }} :
                                            @foreach($application->applicationDestFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.security_need') }}</th>
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
                                        <th>{{ trans('cruds.application.fields.external') }}</th>
                                        <td>{{ $application->external}}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.processes') }}</th>
                                        <td>
                                            @foreach($application->processes as $process)
                                                <a href="/admin/report/information_system#PROCESS{{$process->id}}">{{ $process->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.services') }}</th>
                                        <td>
                                            @foreach($application->services as $service)
                                                <a href="#SERVICE{{$service->id}}">{{ $service->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.application.fields.databases') }}</th>
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
                                        <th>{{ trans('cruds.application.fields.logical_servers') }}</th>
                                        <td>
                                            @foreach($application->logical_servers as $logical_server)
                                                <a href="/admin/report/logical_infrastructure#LOGICALERVER{{$logical_server->id}}">{{ $logical_server->name }}</a>
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
            @if($applicationServices->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.applicationService.title') }}
                </div>

                <div class="card-body">
                    <p>{{ trans('cruds.applicationService.description') }}</p>
                      @foreach($applicationServices as $applicationService)
                      <div class="row">
                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="SERVICE{{$applicationService->id}}">
                                    <th colspan="2">
                                        <a href="/admin/application-services/{{ $applicationService->id }}">{{ $applicationService->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">{{ trans('cruds.applicationService.fields.description') }}</th>
                                        <td>{!! $applicationService->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.applicationService.fields.modules') }}</th>
                                        <td>
                                            @foreach($applicationService->modules as $module)
                                                <a href="#MODULE{{ $module->id }}">{{ $module->name }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.applicationService.fields.flux') }}</th>
                                        <td>
                                            {{ trans('cruds.flux.fields.source') }} :
                                            @foreach($applicationService->serviceSourceFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                            <br>{{ trans('cruds.flux.fields.destination') }} :
                                            @foreach($applicationService->serviceDestFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.applicationService.fields.exposition') }}</th>
                                        <td>{{ $applicationService->exposition }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.applicationService.fields.applications') }}</th>
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
            @if($applicationModules->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.applicationModule.title') }}
                </div>

                <div class="card-body">
                    <p>{{ trans('cruds.applicationModule.description') }}</p>
                        @foreach($applicationModules as $applicationModule)
                          <div class="row">
                            <div class="col-sm-6">
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="MODULE{{$applicationModule->id}}">
                                        <th colspan="2">
                                            <a href="/admin/application-modules/{{ $applicationModule->id }}">{{ $applicationModule->name }}</a>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="30%">{{ trans('cruds.applicationModule.fields.description') }}</th>
                                            <td>{!! $applicationModule->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.applicationModule.fields.services') }}</th>
                                            <td>
                                                @foreach($applicationModule->applicationServices as $service)
                                                    <a href="#MODULE{{ $service->id }}">{{ $service->name }}</a>
                                                    @if(!$loop->last)
                                                    ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.flux.title') }}</th>
                                            <td>
                                                {{ trans('cruds.flux.fields.source') }} :
                                                @foreach($applicationModule->moduleSourceFluxes as $flux)
                                                    <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                    @if (!$loop->last)
                                                    ,
                                                    @endif
                                                @endforeach
                                                <br>{{ trans('cruds.flux.fields.destination') }} :
                                                @foreach($applicationModule->moduleSourceFluxes as $flux)
                                                    <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
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
            @if($databases->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.database.title') }}
                </div>

                <div class="card-body">
                    <p>{{ trans('cruds.database.description') }}</p>
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
                                        <th width="30%">{{ trans('cruds.database.fields.description') }}</th>
                                        <td>{!! $database->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.database.fields.entities') }}</th>
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
                                        <th>{{ trans('cruds.database.fields.entity_resp') }}</th>
                                        <td>
                                            @if ($database->entity_resp!=null)
                                            <a href="/admin/report/ecosystem#ENTITY{{ $database->entity_resp->id }}">{{ $database->entity_resp->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.database.fields.responsible') }}</th>
                                        <td>{{ $database->responsible }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.database.fields.type') }}</th>
                                        <td>{{ $database->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.flux.title') }}</th>
                                        <td>
                                            {{ trans('cruds.flux.fields.source') }} :
                                            @foreach($database->databaseSourceFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                            <br>{{ trans('cruds.flux.fields.destination') }} :
                                            @foreach($database->databaseDestFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>{{ trans('cruds.database.fields.informations') }}</th>
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
                                        <th>{{ trans('cruds.database.fields.security_need') }}</th>
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
                                        <th>{{ trans('cruds.database.fields.external') }}</th>
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
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- <script src="https://d3js.org/d3.v7.min.js"></script> -->
<script src="/js/d3.v5.min.js"></script>
<!-- https://unpkg.com/@hpcc-js/wasm@0.3.11/dist/index.min.js -->
<script src="/js/index.min.js"></script>
<!--<script src="https://unpkg.com/d3-graphviz@4.0.0/build/d3-graphviz.js"></script>-->
<script src="/js/d3-graphviz.js"></script>

<script>
let dotSrc=`
digraph  {
    @can('application_access')
    @foreach($applications as $application)
            A{{ $application->id }} [label="{{ $application->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/application.png" href="#APPLICATION{{$application->id}}"]
    @endforEach
    @endcan
    @can('application_service_access')
    @foreach($applicationServices as $service)
            S{{ $service->id }} [label="{{ $service->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/applicationservice.png" href="#SERVICE{{$service->id}}"]
    @endforeach
    @endcan
    @can('application_module_access')
    @foreach($applicationModules as $module)
            M{{ $module->id }} [label="{{ $module->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/applicationmodule.png" href="#MODULE{{$module->id}}"]
    @endforeach
    @endcan
    @can('database_access')
    @foreach($databases as $database)
        DB{{ $database->id }} [label="{{ $database->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/database.png" href="#DATABASE{{$database->id}}"]
    @endforeach
    @endcan
    @can('flux_access')
    @foreach($flows as $flow)
        @if ((($flow->database_source_id!=null)||($flow->module_source_id!=null)||($flow->service_source_id!=null)||($flow->application_source_id!=null))&&(($flow->database_dest_id!=null)||($flow->module_dest_id!=null)||($flow->service_dest_id!=null)||($flow->application_dest_id!=null)))
                @if ($flow->database_source_id!=null)
                DB{{ $flow->database_source_id }}
                @elseif ($flow->module_source_id!=null)
                M{{ $flow->module_source_id }}
                @elseif ($flow->service_source_id!=null)
                S{{ $flow->service_source_id }}
                @elseif ($flow->application_source_id!=null)
                A{{ $flow->application_source_id }}
                @endif
                ->
                @if ($flow->database_dest_id!=null)
                DB{{ $flow->database_dest_id }}
                @elseif ($flow->module_dest_id!=null)
                M{{ $flow->module_dest_id }}
                @elseif ($flow->service_dest_id!=null)
                S{{ $flow->service_dest_id }}
                @elseif ($flow->application_dest_id!=null)
                A{{ $flow->application_dest_id }}
                @endif
        [ label="{{ $flow->name }}"
        @if ($flow->bidirectional)
            dir="both"
        @endif
        href="#FLOW{{$flow->id}}"]
        @endif
    @endforEach
    @endcan
}`;

d3.select("#graph").graphviz()
    .addImage("/images/application.png", "64px", "64px")
    .addImage("/images/applicationservice.png", "64px", "64px")
    .addImage("/images/applicationmodule.png", "64px", "64px")
    .addImage("/images/database.png", "64px", "64px")
    .engine("circo")
    .renderDot(dotSrc);
</script>
@parent
@endsection
