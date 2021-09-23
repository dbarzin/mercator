@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Vue du Système d'information
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
                                        Macro-processus :
                                        <select name="macroprocess" onchange="this.form.process.value=-1;this.form.submit()">
                                            <option value="-1">-- All --</option>
                                            @foreach ($all_macroprocess as $macroprocess)
                                                <option value="{{$macroprocess->id}}" {{ Session::get('macroprocess')==$macroprocess->id ? "selected" : "" }}>{{ $macroprocess->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        Processus :
                                        <select name="process" onchange="this.form.submit()">
                                            <option value="-1">-- All --</option>
                                            @if ($all_process!=null)
                                                @foreach ($all_process as $process)
                                                    <option value="{{$process->id}}" {{ Session::get('process')==$process->id ? "selected" : "" }}>{{ $process->identifiant }}</option>
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

            @if ((auth()->user()->granularity>=2)&&($macroProcessuses->count()>0))
            <div class="card">
                <div class="card-header">
                    Macro-processus
                </div>
                <div class="card-body">
                    <p>Ensemble des macrprocessus.</p>
                      @foreach($macroProcessuses as $macroProcess)
                      <div class="row">
                        <div class="col-sm-6">                        
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="MACROPROCESS{{ $macroProcess->id }}">
                                <th colspan="2">
                                    <a href="/admin/macro-processuses/{{ $macroProcess->id }}/edit">{{ $macroProcess->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>Description</b></td>
                                    <td>{!! $macroProcess->description !!}</td>
                                </tr>
                                <tr>
                                    <td><b>Éléments entrants et sortants</b></td>
                                    <td>{!! $macroProcess->io_elements !!}</td>
                                </tr>
                                <tr>
                                    <td><b>Besoin de sécurité</b></td>
                                    <td>
                                        {{ trans('global.confidentiality') }} :
                                            {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                            [$macroProcess->security_need_c] ?? "" }}
                                        <br>
                                        {{ trans('global.integrity') }} :
                                            {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                            [$macroProcess->security_need_i] ?? "" }}
                                        <br>
                                        {{ trans('global.availability') }} :
                                            {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                            [$macroProcess->security_need_a] ?? "" }}
                                        <br>
                                        {{ trans('global.tracability') }} :
                                            {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                            [$macroProcess->security_need_t] ?? "" }} 
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Propritétaire</b></td>
                                    <td>{{ $macroProcess->owner }}</td>
                                </tr>
                                <tr>
                                    <td><b>Processus</b></td>
                                    <td>
                                    @foreach($macroProcess->processes as $process)
                                        <a href="#PROCESS{{ $process->id }}">{{ $process->identifiant }}</a>
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

            @if ($processes->count()>0)
            <div class="card">
                <div class="card-header">
                    Processus
                </div>
                <div class="card-body">
                    <p>Ensemble d’activités concourant à un objectif. Le processus produit des informations (de sortie) à valeur ajoutée (sous forme de livrables) à partir d’informations (d’entrées) produites par d’autres processus.</p>
                        @foreach($processes as $process)

                          <div class="row">
                            <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="PROCESS{{ $process->id }}">
                                    <th colspan="2">
                                        <a href="/admin/processes/{{ $process->id }}/edit">{{ $process->identifiant }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="20%"><b>Description</b></td>
                                        <td>{!! $process->description !!}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Éléments entrants et sortants</b></td>
                                        <td>{!! $process->in_out !!}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Activités</b></td>
                                        <td>
                                            @foreach($process->activities as $activity)
                                                <a href="#ACTIVITY{{ $activity->id }}">{{ $activity->name }}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Entité associées</b></td>
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
                                        <td><b>Applications qui le soutiennent</b></td>
                                        <td>
                                            @foreach($process->processesMApplications as $application)
                                                <a href="/admin/report/applications#APPLICATION{{$application->id}}">{{$application->name}}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Besoin de scurité</b></td>
                                        <td>
                                        {{ trans('global.confidentiality') }} :
                                            {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                            [$process->security_need_c] ?? "" }}
                                        <br>
                                        {{ trans('global.integrity') }} :
                                            {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                            [$process->security_need_i] ?? "" }}
                                        <br>
                                        {{ trans('global.availability') }} :
                                            {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                            [$process->security_need_a] ?? "" }}
                                        <br>
                                        {{ trans('global.tracability') }} :
                                            {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                            [$process->security_need_t] ?? "" }} 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Propriétaire</b></td>
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

            @if ($activities->count()>0)
            <div class="card">
                <div class="card-header">
                    Activité
                </div>
                <div class="card-body">
                    <p>Étape nécessaire à la réalisation d’un processus. Elle correspond à un savoir-faire spéciﬁque et pas forcément à une structure organisationnelle de l’entreprise.</p>
                        @foreach($activities as $activity)
                      <div class="row">
                        <div class="col-sm-6">                        
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="ACTIVITY{{ $activity->id }}">
                                <th colspan="2">
                                    <a href="/admin/activities/{{ $activity->id }}/edit">{{ $activity->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>Description</b></td>
                                    <td>{!! $activity->description !!}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Liste des opérations</b>
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

            @if ($operations->count()>0)
            <div class="card">
                <div class="card-header">
                    Opérations
                </div>
                <div class="card-body">
                    <p>Etape d’une procédure correspondant à l’intervention d’un acteur dans le cadre d’une activité.</p>
                        @foreach($operations as $operation)
                      <div class="row">
                        <div class="col-sm-6">                        
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="OPERATION{{ $operation->id }}">
                                <th colspan="2">
                                    <a href="/admin/operations/{{ $operation->id }}/edit">{{ $operation->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>Description</b></td>
                                    <td>{!! $operation->description !!}</td>
                                </tr>
                                <tr>
                                    <td>
                                        <b>Liste des tâches qui la composent</b>
                                    </td>
                                    <td>
                                        @foreach($operation->tasks as $task)                                
                                            <a href="#TASK{{$task->id}}">{{$task->nom}}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif                                        
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Liste des acteurs qui interviennent</b></td>
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

            @if ($tasks->count()>0)
            <div class="card">
                <div class="card-header">
                    Tâche
                </div>
                <div class="card-body">
                    <p>Activité élémentaire exercée par une fonction organisationnelle et constituant une unité indivisible de travail dans la chaîne de valeur ajoutée d’un processus</p>
                        @foreach($tasks as $task)
                          <div class="row">
                            <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="TASK{{ $task->id }}">
                                    <th colspan="2">
                                        <a href="/admin/tasks/{{ $task->id }}/edit">{{ $task->nom }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="20%"><b>Description</b></td>
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

            @if ($actors->count()>0)
            <div class="card">
                <div class="card-header">
                    Acteurs
                </div>
                <div class="card-body">
                    <p>Représentant d’un rôle métier qui exécute des opérations, utilise des applications et prend des décisions dans le cadre des processus. Ce rôle peut être porté par une personne, un groupe de personnes ou une entité</p>
                        @foreach($actors as $actor)
                          <div class="row">
                            <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="ACTOR{{ $actor->id }}">
                                    <th colspan="2">
                                        <a href="/admin/actors/{{ $actor->id }}/edit">{{ $actor->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="20%"><b>Nature</b></td>
                                        <td>{{ $actor->nature }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Type</b></td>
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

            @if ($informations->count()>0)
            <div class="card">
                <div class="card-header">
                    Informations
                </div>
                <div class="card-body">
                    <p>Donnée faisant l’objet d’un traitement informatique.</p>
                        @foreach($informations as $information)
                          <div class="row">
                            <div class="col-sm-6">                        
                            <table class="table table-bordered table-striped table-hover">
                                <thead id="INFORMATION{{ $information->id }}">
                                    <th colspan="2">
                                        <a href="/admin/information/{{ $information->id }}/edit">{{ $information->name }}</a>
                                    </th>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td width="20%"><b>Description</b></td>
                                        <td>{!! $information->description !!}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Propriétaire</b></td>
                                        <td>{{ $information->owner }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Administrateur</b></td>
                                        <td>{{ $information->administrator }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Stockage</b></td>
                                        <td>{{ $information->storage }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Processus liés</b></td>
                                        <td>
                                            @foreach($information->processes as $process)
                                                <a href="#PROCESS{{ $process->id}}">{{ $process->identifiant}}</a>
                                                @if (!$loop->last)
                                                ,
                                                @endif                                                                                        
                                            @endforeach
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Besoins de sécurité</b></td>
                                        <td>
                                            {{ trans('global.confidentiality') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$information->security_need_c] ?? "" }}
                                            <br>
                                            {{ trans('global.integrity') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$information->security_need_i] ?? "" }}
                                            <br>
                                            {{ trans('global.availability') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$information->security_need_a] ?? "" }}
                                            <br>
                                            {{ trans('global.tracability') }} :
                                                {{ array(1=>trans('global.low'),2=>trans('global.medium'),3=>trans('global.strong'),4=>trans('global.very_strong'))
                                                [$information->security_need_t] ?? "" }} 
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><b>Sensibilité</b></td>
                                        <td>{{ $information->sensibility }}</td>
                                    </tr>
                                    <tr>
                                        <td><b>Contraintes règlementaires et normatives</b></td>
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
d3.select("#graph").graphviz()
    .addImage("/images/macroprocess.png", "64px", "64px")
    .addImage("/images/process.png", "64px", "64px")
    .addImage("/images/activity.png", "64px", "64px")
    .addImage("/images/operation.png", "64px", "64px")
    .addImage("/images/task.png", "64px", "64px")
    .addImage("/images/actor.png", "64px", "64px")
    .addImage("/images/information.png", "64px", "64px")
        .renderDot("digraph  {\
            <?php  $i=0; ?>\
            @if (auth()->user()->granularity>=2)\
            @foreach($macroProcessuses as $macroProcess) \
                MP{{ $macroProcess->id }} [label=\"{{ $macroProcess->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/macroprocess.png\"  href=\"#MACROPROCESS{{ $macroProcess->id }}\"]\
            @endforeach\
            @endif\
            @foreach($processes as $process)\
                P{{ $process->id }} [label=\"{{ $process->identifiant }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/process.png\"  href=\"#PROCESS{{ $process->id }}\"]\
                @foreach($process->activities as $activity)\
                    P{{$process->id}} -> A{{$activity->id}}\
                @endforeach\
                @foreach($process->processInformation as $information)\
                    P{{ $process->id }} -> I{{ $information->id }}\
                @endforeach\
                @if (auth()->user()->granularity>=2)\
                @if ($process->macroprocess_id!=null) \
                    MP{{ $process->macroprocess_id }} -> P{{$process->id}}\
                @endif\
                @endif\
            @endforeach\
            @foreach($activities as $activity)\
                A{{ $activity->id }} [label=\"{{ $activity->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/activity.png\"  href=\"#ACTIVITY{{ $activity->id }}\"]\
                @foreach($activity->operations as $operation)\
                    A{{ $activity->id }} -> O{{ $operation->id }}\
                @endforeach\
            @endforeach\
            @foreach($operations as $operation)\
                O{{ $operation->id }} [label=\"{{ $operation->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/operation.png\"  href=\"#OPERATION{{ $operation->id }}\"]\
                @foreach($operation->tasks as $task)\
                    O{{ $operation->id }} -> T{{ $task->id }}\
                @endforeach\
                @foreach($operation->actors as $actor)\
                    O{{ $operation->id }} -> ACT{{ $actor->id }}\
                @endforeach\
            @endforeach\
            @foreach($tasks as $task)\
                T{{ $task->id }} [label=\"{{ $task->nom }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/task.png\"  href=\"#TASK{{ $task->id }}\"]\
            @endforeach\
            @foreach($actors as $actor)\
                ACT{{ $actor->id }} [label=\"{{ $actor->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/actor.png\"  href=\"#ACTOR{{ $actor->id }}\"]\
            @endforeach\
            @foreach($informations as $information)\
                I{{ $information->id }} [label=\"{{ $information->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/information.png\"  href=\"#INFORMATION{{ $information->id }}\"]\
            @endforeach\
        }");
</script>
@parent
@endsection