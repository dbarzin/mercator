@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Vue des flux applicatifs
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
                                    Blocs applicatif :
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
                                    Applications :
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
                            </tr>
                        </table>                       
                    </form>
                </div>
                <div id="graph"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Flux
                </div>

                <div class="card-body">
                    <p>Echange d’informations entre un émetteur ou un récepteur (service applicatif, application ou acteur).</p>
                    @foreach($flows as $flux)
                      <div class="row">
                        <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="FLOW{{$flux->id}}">
                                    <th colspan="2">
                                        <a href="/admin/fluxes/{{ $flux->id }}/edit">{{ $flux->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">Description</th>
                                        <td>{!! $flux->description !!}</td>
                                    </tr>
                                    @if ($flux->application_source!=null) 
                                    <tr>
                                        <th>Application Source</th>
                                        <td><a href="#APPLICATION{{$flux->application_source->id}}">{{$flux->application_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->service_source!=null)
                                    <tr>
                                        <th>Service Source</th>
                                        <td><a href="#SERVICE{{$flux->service_source->id}}">{{$flux->service_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->module_source!=null) 
                                    <tr>
                                        <th>Module Source</th>
                                        <td><a href="#MODULE{{$flux->module_source->id}}">{{$flux->module_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->database_source!=null) 
                                    <tr>
                                        <th>Database Source</th>
                                        <td><a href="#DATABASE{{$flux->database_source->id}}">{{$flux->database_source->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->application_dest!=null) 
                                    <tr>
                                        <th>Application destinataire</th>
                                        <td><a href="#APPLICATION{{$flux->application_dest->id}}">{{$flux->application_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->service_dest!=null) 
                                    <tr>
                                        <th>Service destinataire</th>
                                        <td><a href="#SERVICE{{$flux->service_dest->id}}">{{$flux->service_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->module_dest!=null) 
                                    <tr>
                                        <th>Module destinataire</th>
                                        <td><a href="#MODULE{{$flux->module_dest->id}}">{{$flux->module_dest->name}}</a></td>
                                    </tr>
                                    @endif

                                    @if ($flux->database_dest!=null) 
                                    <tr>
                                        <th>Database destinataire</th>
                                        <td><a href="#DATABASE{{$flux->database_dest->id}}">{{$flux->database_dest->name}}</a></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach
                </div>
            </div>


            <div class="card">
                <div class="card-header">
                    Applications
                </div>

                <div class="card-body">
                    <p>Ensemble cohérent d’objets informatiques (exécutables, programmes, données...). Elle constitue un regroupement de services applicatifs.</p>
                     @foreach($applications as $application)
                      <div class="row">
                        <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="APPLICATION{{$application->id}}">
                                    <th colspan="2">
                                        <a href="/admin/applications/{{ $application->id }}/edit">{{ $application->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">Description</th>
                                        <td>{!! $application->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Liste de la (des) entité(s) utilisatrice(s)</th>
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
                                        <th>Entité responsable de l'exploitation</th>
                                        <td>
                                            @if (isset($application->entity_resp)) 
                                                <a href="/admin/report/ecosystem#ENTITY{{$application->entity_resp->id}}">{{  $application->entity_resp->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Responsable SSI</th>
                                        <td>{{ $application->responsible}}</td>
                                    </tr>
                                    <tr>
                                        <th>Type de technologie</th>
                                        <td> {{ $application->technology}}</td>
                                    </tr>
                                    <tr>
                                        <th>Type d’application</th>
                                        <td> {{ $application->type}}</td>
                                    </tr>
                                    <tr>
                                        <th>Volume d’utilisateurs et profils</th>
                                        <td> {{ $application->users}}</td>
                                    </tr>
                                    <tr>
                                        <th>Documentation</th>
                                        <td><a href="{{ $application->documentation}}">{{ $application->documentation}}</a></td>
                                    </tr>
                                    <tr>
                                        <th>Flux associés</th>
                                        <td>
                                            Source :
                                            @foreach($application->applicationSourceFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach                 
                                            <br>Destinataire :
                                            @foreach($application->applicationDestFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Besoins de sécurité</th>
                                        <td>
                                            @if ($application->security_need==1) 
                                                Public
                                            @elseif ($application->security_need==2)
                                                Internal
                                            @elseif ($application->security_need==3)
                                                Confidential
                                            @elseif ($application->security_need==4)
                                                Secret
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Exposition à l’externe</th>
                                        <td>{{ $application->external}}</td>
                                    </tr>
                                    <tr>
                                        <th>Liste des processus utilisant l’application</th>
                                        <td>
                                            @foreach($application->processes as $process)
                                                <a href="/admin/report/information_system#PROCESS{{$process->id}}">{{ $process->identifiant }}</a>
                                                @if(!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>                                        
                                    </tr>
                                    <tr>
                                        <th>Liste des services applicatifs délivrés par l’application</th>
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
                                        <th>Liste des bases de données utilisées par l’application</th>
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
                                        <th>Liste des serveurs logiques soutenant l’application</th>
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
            <div class="card">
                <div class="card-header">
                    Services applicatif
                </div>

                <div class="card-body">
                    <p>Élément de découpage de l’application mis à disposition de l’utilisateur final dans le cadre de son travail. Un service applicatif peut, par exemple, être un service dans le nuage (Cloud) </p>
                      @foreach($applicationServices as $applicationService)
                      <div class="row">
                        <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="SERVICE{{$applicationService->id}}">
                                    <th colspan="2">
                                        <a href="/admin/application-services/{{ $applicationService->id }}/edit">{{ $applicationService->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">Description</th>
                                        <td>{!! $applicationService->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Liste des modules qui le composent</th>
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
                                        <th>Flux associés</th>
                                        <td>
                                            Source :
                                            @foreach($applicationService->serviceSourceFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach                 
                                            <br>Destinataire :
                                            @foreach($applicationService->serviceDestFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Exposition à l’externe</th>
                                        <td>{{ $applicationService->external }}</td>
                                    </tr>
                                    <tr>
                                        <th>Applications qui utilisent ce service</th>
                                        <td>
                                            @foreach($applicationService->applications as $application)
                                                <a href="APPLICATION{{ $application->id }}">{{ $application->name }}</a>
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
            <div class="card">
                <div class="card-header">
                    Modules applicatif
                </div>

                <div class="card-body">
                    <p>Composant d’une application caractérisé par une cohérence fonctionnelle en matière d’informatique et une homogénéité technologique.</p>
                        @foreach($applicationModules as $applicationModule)
                          <div class="row">
                            <div class="col-sm-6">                        
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="MODULE{{$applicationModule->id}}">
                                        <th colspan="2">
                                            <a href="/admin/application-modules/{{ $applicationModule->id }}/edit">{{ $applicationModule->name }}</a><br>
                                        </th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th width="30%">Description</th>
                                            <td>{!! $applicationModule->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>Services qui utilisent ce module</th>
                                            <td>
                                                @foreach($applicationModule->modulesApplicationServices as $service)
                                                    <a href="#MODULE{{ $service->id }}">{{ $service->name }}</a>
                                                    @if(!$loop->last)
                                                    ,
                                                    @endif                                                
                                                @endforeach
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Flux associés</th>
                                            <td>
                                                Source :
                                                @foreach($applicationModule->moduleSourceFluxes as $flux)
                                                    <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                    @if (!$loop->last)
                                                    ,
                                                    @endif
                                                @endforeach                 
                                                <br>Destinataire :
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
            <div class="card">
                <div class="card-header">
                    Bases de données
                </div>

                <div class="card-body">
                    <p>Ensemble structuré et ordonné d’informations destinées à être exploitées informatiquement.</p>
                    @foreach($databases as $database)
                      <div class="row">
                        <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="DATABASE{{$database->id}}">
                                    <th colspan="2">
                                        <a href="/admin/databases/{{ $database->id }}/edit">{{ $database->name }}</a><br>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th width="30%">Description</th>
                                        <td>{!! $database->description !!}</td>
                                    </tr>
                                    <tr>
                                        <th>Entité(s) utilisatrice(s)</th>
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
                                        <th>Entité resposanble de l'exploitation</th>
                                        <td>
                                            @if ($database->entity_resp!=null)
                                            <a href="/admin/report/ecosystem#ENTITY{{ $database->entity_resp->id }}">{{ $database->entity_resp->name }}</a>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Responsable SSI</th>
                                        <td>{{ $database->responsible }}</td>
                                    </tr>
                                    <tr>
                                        <th>Type de technologie</th>
                                        <td>{{ $database->type }}</td>
                                    </tr>
                                    <tr>
                                        <th>Flux associés</th>
                                        <td>
                                            Source :
                                            @foreach($database->databaseSourceFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach                 
                                            <br>Destinataire :
                                            @foreach($database->databaseDestFluxes as $flux)
                                                <a href="#FLOW{{$flux->id}}">{{ $flux->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach                                            
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Liste des informations contenues :</th>
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
                                        <th>Besoins de sécurité (DICT)</th>
                                        <td>
                                            {{ $database->security_need }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th>Exposition à l’externe</th>
                                        <td>{{ $database->external }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
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
d3.select("#graph").graphviz()
    .addImage("/images/application.png", "64px", "64px")
    .addImage("/images/applicationservice.png", "64px", "64px")
    .addImage("/images/applicationmodule.png", "64px", "64px")
    .addImage("/images/database.png", "64px", "64px")
    .engine("circo")
    .renderDot("digraph  {\
            @foreach($applications as $application) \
                    A{{ $application->id }} [label=\"{{ $application->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/application.png\" href=\"#APPLICATION{{$application->id}}\"] \
            @endforEach\
            @foreach($applicationServices as $service) \
                    S{{ $service->id }} [label=\"{{ $service->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/applicationservice.png\" href=\"#SERVICE{{$service->id}}\"]\
            @endforeach\
            @foreach($applicationModules as $module) \
                    M{{ $module->id }} [label=\"{{ $module->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/applicationmodule.png\" href=\"#MODULE{{$module->id}}\"]\
            @endforeach\
            @foreach($databases as $database) \
                DB{{ $database->id }} [label=\"{{ $database->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/database.png\" href=\"#DATABASE{{$database->id}}\"]\
            @endforeach\
            @foreach($flows as $flow) \
                @if ((($flow->database_source_id!=null)||($flow->module_source_id!=null)||($flow->service_source_id!=null)||($flow->application_source_id!=null))&&(($flow->database_dest_id!=null)||($flow->module_dest_id!=null)||($flow->service_dest_id!=null)||($flow->application_dest_id!=null)))\
                        @if ($flow->database_source_id!=null) \
                        DB{{ $flow->database_source_id }} \
                        @elseif ($flow->module_source_id!=null) \
                        M{{ $flow->module_source_id }} \
                        @elseif ($flow->service_source_id!=null) \
                        S{{ $flow->service_source_id }} \
                        @elseif ($flow->application_source_id!=null) \
                        A{{ $flow->application_source_id }} \
                        @endif\
                        -> \
                        @if ($flow->database_dest_id!=null) \
                        DB{{ $flow->database_dest_id }} \
                        @elseif ($flow->module_dest_id!=null) \
                        M{{ $flow->module_dest_id }} \
                        @elseif ($flow->service_dest_id!=null) \
                        S{{ $flow->service_dest_id }} \
                        @elseif ($flow->application_dest_id!=null) \
                        A{{ $flow->application_dest_id }} \
                        @endif\
                [ label=\"{{ $flow->name }}\" href=\"#FLOW{{$flow->id}}\"]\
                @endif\
            @endforEach\
        }");
</script>
@parent
@endsection
