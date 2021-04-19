@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Vue de l'écosystème
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div id="graph"></div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    Entités
                </div>
                <div class="card-body">
                    <p>Partie de l’organisme (ex. : filiale, département, etc.) ou système d’information en relation avec le SI qui vise à être cartographié.</p>
                        @foreach($entities as $entity)
                      <div class="row">
                        <div class="col-sm-6">                        
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="ENTITY{{ $entity->id }}">
                                <th colspan="2">
                                    <a href="/admin/entities/{{ $entity->id }}/edit">{{ $entity->name }}</a><br>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>Description</b></td>
                                    <td>{!! $entity->description !!}</td>
                                </tr>
                                <tr>
                                    <td><b>Niveau de sécurité</b></td>
                                    <td>{!! $entity->security_level !!}</td>
                                </tr>
                                <tr>
                                    <td><b>Point de conatct</b></td>
                                    <td>{!! $entity->contact_point !!}</td>
                                </tr>
                                <tr>
                                    <td><b>Relations</b></td>
                                    <td>
                                        @foreach ($entity->sourceRelations as $relation)
                                            <a href="#RELATION{{ $relation->id }}">{{ $relation->name }}</a>
                                            -> 
                                            <a href="#ENTITY{{ $relation->destination_id }}">{{ $relation->destination->name ?? '' }}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                				        @if (($entity->sourceRelations->count()>0)&&($entity->destinationRelations->count()>0))	
                                        , <br>
                                        @endif
                                        @foreach ($entity->destinationRelations as $relation)
                                            <a href="#ENTITY{{ $relation->source_id }}">{{ $relation->source->name ?? '' }}</a>
                                            <-
                                            <a href="#RELATION{{ $relation->id }}">{{ $relation->name }}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Processus soutenus</b></td>
                                    <td>
                                        @foreach ($entity->entitiesProcesses as $process)
                                            <a href="/admin/report/information_system#PROCESS{{ $process->id }}">{{ $process->identifiant }}</a>
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
                    Relations
                </div>
                <div class="card-body">
                    <p>Lien entre deux entités ou systèmes.</p>
                        @foreach($relations as $relation)
                      <div class="row">
                        <div class="col-sm-6">                        
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="RELATION{{$relation->id}}">
                                <th colspan="2">
                                <a href="/admin/relations/{{ $relation->id }}/edit">{{ $relation->name }}</a> <br>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>Description</b></td>
                                    <td>{!! $relation->description !!}</td>
                                </tr>
                                <tr>                                    
                                    <td><b>Type</b></td>
                                    <td>{{ $relation->type }}</td>
                                </tr>
                                <tr>
                                    <td><b>Importance</b></td>
                                    <td>
                                    @if ($relation->inportance==1) 
                                        Faible
                                    @elseif ($relation->inportance==2)
                                        Moyen
                                    @elseif ($relation->inportance==3)
                                        Fort
                                    @elseif ($relation->inportance==4)
                                        Critique
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>Lien</b></td>
                                    <td>
                                        <a href="#ENTITY{{ $relation->source_id }}">
                                            {{ $relation->source->name ?? ""}}
                                        </a>
                                        ->
                                        <a href="#ENTITY{{ $relation->destination_id }}">
                                            {{ $relation->destination->name ?? "" }} 
                                        </a>
                                    </td>
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
<!-- //d3js.org/d3.v5.min.js -->
<script src="/js/d3.v5.min.js"></script>
<!-- https://unpkg.com/@hpcc-js/wasm@0.3.11/dist/index.min.js -->
<script src="/js/index.min.js"></script>
<!-- https://unpkg.com/d3-graphviz@3.0.5/build/d3-graphviz.js -->
<script src="/js/d3-graphviz.js"></script>

<script>
d3.select("#graph").graphviz()
    .addImage("/images/entity.png", "64px", "64px")
    .renderDot("digraph  {\
            <?php  $i=0; ?>\
            @foreach($entities as $entity) \
                E{{ $entity->id }} [label=\"{{ $entity->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/entity.png\" href=\"#ENTITY{{$entity->id}}\"]\
            @endforEach\
            @foreach($relations as $relation) \
                E{{ $relation->source_id }} -> E{{ $relation->destination_id }} [label=\"{{ $relation ->name }}\" href=\"#RELATION{{$relation->id}}\"]\
            @endforEach\
        }");

</script>
@parent
@endsection
