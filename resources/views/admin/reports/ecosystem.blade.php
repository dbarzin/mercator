@extends('layouts.admin')
@section('content')
@if (Gate::check('entity_show') || Gate::check('relation_show'))
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.menu.ecosystem.title') }}
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
		    
		    <div class="col-sm-5">
			<form action="/admin/report/ecosystem">
			    <table class="table table-bordered table-striped">
				<tr>
				    <td>externe à l'organisme
				    </td>
				</tr>
				<tr>
				    <td>
					<select name="perimeter" onchange="this.form.submit()">
					    @if (Session::get('perimeter')==null)
						<option value="All" selected >All</option>
					    @else
						@foreach (["All", "Internes", "Externes"] as $choice)
						    <option value="{{ $choice }}" {{ Session::get('perimeter')==$choice? "selected" : "" }}
						    >{{ $choice }}</option>
					    @endforeach
					    @endif
					</select>
				    </td>
				</tr>
			    </table>
			</form>
		    </div>
                    <div id="graph"></div>
                </div>
            </div>

            @can('entity_show')
            @if($entities->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.entity.title') }}
                </div>
                <div class="card-body">
                    <p>{{ trans('cruds.entity.description') }}</p>
                        @foreach($entities as $entity)
                      <div class="row">
                        <div class="col-sm-6">                        
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="ENTITY{{ $entity->id }}">
                                <th colspan="2">
                                    @can('entity_edit')
                                    <a href="{{ route('admin.entities.edit',$entity->id) }}">{{ $entity->name }}</a>
                                    @else
                                    <a href="{{ route('admin.entities.show',$entity->id) }}">{{ $entity->name }}</a>
                                    @endcan
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>{{ trans('cruds.entity.fields.description') }}</b></td>
                                    <td>{!! $entity->description !!}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.entity.fields.is_external') }}</b></td>
                                    <td>{!! $entity->is_external !!}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.entity.fields.security_level') }}</b></td>
                                    <td>{!! $entity->security_level !!}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.entity.fields.contact_point') }}</b></td>
                                    <td>{!! $entity->contact_point !!}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.entity.fields.relations') }}</b></td>
                                    <td>
                                        @foreach ($entity->sourceRelations as $relation)
                                            <a href="#RELATION{{ $relation->id }}">{{ $relation->name }}</a>
                                            -> 
                                            <a href="#ENTITY{{ $relation->destination_id }}">{{ $relation->destination->name ?? '' }}</a>
                                            @if (!$loop->last)
                                            <br>
                                            @endif
                                        @endforeach
                                        @if (($entity->sourceRelations->count()>0)&&($entity->destinationRelations->count()>0)) 
                                        <br>
                                        @endif
                                        @foreach ($entity->destinationRelations as $relation)
                                            <a href="#ENTITY{{ $relation->source_id }}">{{ $relation->source->name ?? '' }}</a>
                                            <-
                                            <a href="#RELATION{{ $relation->id }}">{{ $relation->name }}</a>
                                            @if (!$loop->last)
                                            <br>
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.entity.fields.processes') }}</b></td>
                                    <td>
                                        @foreach ($entity->entitiesProcesses as $process)
                                            <a href="/admin/report/information_system#PROCESS{{ $process->id }}">{{ $process->identifiant }}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.entity.fields.exploits') }}</b></td>
                                    <td>
                                        @foreach($entity->applications as $application)
                                            <a href="/admin/report/applications#APPLICATION{{$application->id}}">{{$application->name}}</a>
                                            @if (!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                        @if (
                                            ($entity->applications->count()>0)&&
                                            ($entity->databases->count()>0)
                                            )
                                            ,<br>
                                        @endif
                                        @foreach($entity->databases as $database)
                                            <a href="/admin/report/applications#DATABASE{{$database->id}}">{{$database->name}}</a>
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

            @can('relation_show')
            @if($relations->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.relation.title') }}
                </div>
                <div class="card-body">
                    <p>{{ trans('cruds.relation.description') }}</p>
                        @foreach($relations as $relation)
                      <div class="row">
                        <div class="col-sm-6">                        
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="RELATION{{$relation->id}}">
                                <th colspan="2">
                                @can('relation_edit')                                    
                                <a href="{{ route('admin.relations.edit',$relation->id) }}">{{ $relation->name }}</a>
                                @else
                                <a href="{{ route('admin.relations.show',$relation->id) }}">{{ $relation->name }}</a>
                                @endcan                                
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>{{ trans('cruds.relation.fields.description') }}</b></td>
                                    <td>{!! $relation->description !!}</td>
                                </tr>
                                <tr>                                    
                                    <td><b>{{ trans('cruds.relation.fields.type') }}</b></td>
                                    <td>{{ $relation->type }}</td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.relation.fields.importance') }}</b></td>
                                    <td>
                                    @if ($relation->importance==1) 
                                        {{ trans('cruds.relation.fields.importance_level.low') }}
                                    @elseif ($relation->importance==2)
                                        {{ trans('cruds.relation.fields.importance_level.medium') }}
                                    @elseif ($relation->importance==3)
                                        {{ trans('cruds.relation.fields.importance_level.high') }}
                                    @elseif ($relation->importance==4)
                                        {{ trans('cruds.relation.fields.importance_level.critical') }}
                                    @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td><b>{{ trans('cruds.relation.fields.link') }}</b></td>
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
            @endif
            @endcan
        </div>
    </div>
</div>
@endif
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
        @can('entity_show')\
            @foreach($entities as $entity) \
                E{{ $entity->id }} [label=\"{{ $entity->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/entity.png\" href=\"#ENTITY{{$entity->id}}\"]\
            @endforEach\
        @endcan\
        @can('relation_show')\
            @foreach($relations as $relation) \
                E{{ $relation->source_id }} -> E{{ $relation->destination_id }} [label=\"{{ $relation ->name }}\" href=\"#RELATION{{$relation->id}}\"]\
            @endforEach\
        @endcan\
        }");

</script>
@parent
@endsection
