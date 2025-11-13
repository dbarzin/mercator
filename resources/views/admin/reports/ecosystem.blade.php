@extends('layouts.admin')
@section('content')
    @if (Gate::check('entity_show') || Gate::check('relation_show'))
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <form action="/admin/report/ecosystem">
                        <div class="card-header">
                            {{ trans('cruds.menu.ecosystem.title') }}
                        </div>

                        <div class="card-body">
                            @if(session('status'))
                                <div class="alert alert-success" role="alert">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <div class="col-sm-6" style="max-width: 800px;">
                                <table class="table table-bordered table-striped"
                                       style="max-width: 600px; ">
                                    <tr>
                                        <td style="width: 300px;">{{ trans('cruds.entity.filters.title.int/ext') }}
                                        </td>
                                        <td style="width: 300px;">{{ trans('cruds.entity.filters.title.type') }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <select name="perimeter" onchange="this.form.submit()"
                                                    class="form-control select2">
                                                @if (Session::get('perimeter')===null)
                                                    <option value="All" selected>
                                                        {{ trans('cruds.entity.filters.all') }}
                                                    </option>
                                                @else
                                                    @foreach ([
                                                        "All"      => trans('cruds.entity.filters.all'),
                                                        "Externes" => trans('cruds.entity.filters.externes'),
                                                        "Internes" => trans('cruds.entity.filters.internes')] as $key=>$choice)
                                                        <option value="{{ $key }}" {{ Session::get('perimeter')==$key? "selected" : "" }}
                                                        >{{ $choice }}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>
                                            <select name="entity_type" onchange="this.form.submit()"
                                                    class="form-control select2">
                                                <option value="All" {{ Session::get('entity_type')== null ? "selected" : "" }} >{{ trans('cruds.entity.filters.all_types') }}</option>
                                                @foreach ($entityTypes as $type)
                                                    <option value="{{ $type }}" {{ Session::get('entity_type')==$type? "selected" : "" }}
                                                    >{{ $type }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div id="graph-container">
                                <div class="graphviz" id="graph"></div>
                            </div>
                            <div class="row p-1">
                                <div class="col-4">

                                    @php($engines=["dot", "fdp",  "osage", "circo" ])
                                    @php($engine = request()->get('engine', 'dot'))

                                    <label class="inline-flex items-center ps-1 pe-1">
                                        <a href="#" id="downloadSvg"><i class="bi bi-download"></i></a>
                                    </label>

                                    <label class="inline-flex items-center">
                                        Rendu :
                                    </label>
                                    @foreach($engines as $value)
                                        <label class="inline-flex items-center ps-1">
                                            <input
                                                    type="radio"
                                                    name="engine"
                                                    value="{{ $value }}"
                                                    @checked($engine === $value)
                                                    onchange="this.form.submit();"
                                            >
                                            <span>{{ $value }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <br>

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
                                            <table class="table table-bordered table-striped table-hover table-report">
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
                                                    <td><b>{{ trans('cruds.entity.fields.entity_type') }}</b></td>
                                                    <td>{{ $entity->entity_type }}</td>
                                                </tr>

                                                @if ($entity->parentEntity!=null)
                                                    <th>
                                                        {{ trans('cruds.entity.fields.parent_entity') }}
                                                    </th>
                                                    <td>
                                                        <a href="{{ route('admin.entities.show', $entity->parentEntity->id) }}">{{ $entity->parentEntity->name }}</a>
                                                    </td>
                                                @endif

                                                @if ($entity->entities()->count()>0)
                                                    <tr>
                                                        <th>
                                                            {{ trans('cruds.entity.fields.subsidiaries') }}
                                                        </th>
                                                        <td colspan="7">
                                                            @foreach($entity->entities as $e)
                                                                <a href="{{ route('admin.entities.show', $e->id) }}">{{ $e->name }}</a>
                                                                @if(!$loop->last)
                                                                    ,
                                                                @endif
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td><b>{{ trans('cruds.entity.fields.is_external') }}</b></td>
                                                    <td>{{ $entity->is_external ? trans('global.yes') : trans('global.no') }}</td>
                                                </tr>
                                                <tr>
                                                    <td width="20%">
                                                        <b>{{ trans('cruds.entity.fields.description') }}</b></td>
                                                    <td>{!! $entity->description !!}</td>
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
                                                        @foreach ($entity->processes as $process)
                                                            <a href="/admin/report/information_system#PROCESS{{ $process->id }}">{{ $process->name }}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td><b>{{ trans('cruds.entity.fields.exploits') }}</b></td>
                                                    <td>
                                                        @foreach($entity->respApplications as $application)
                                                            <a href="/admin/report/applications#APPLICATION{{$application->id}}">{{$application->name}}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                        @if (
                                                            ($entity->respApplications->count()>0)&&
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
                                            <table class="table table-bordered table-striped table-hover table-report">
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
                                                    <td width="20%">
                                                        <b>{{ trans('cruds.relation.fields.description') }}</b></td>
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
    @endif
@endsection

@section('scripts')
    @vite(['resources/js/d3-viz.js'])
    <script id="dot-input">
        let dotSrc = `
  digraph  {
  @can('entity_show')
        @foreach($entities as $entity)
        E{{ $entity->id }} [label="{{ $entity->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $entity->icon_id === null ? '/images/entity.png' : route('admin.documents.show', $entity->icon_id) }}" href="#ENTITY{{$entity->id}}"]
    @if (($entity->parentEntity!=null)&&($entities->contains("id",$entity->parentEntity->id)))
        E{{ $entity->parentEntity->id }} -> E{{ $entity->id }}
        @endif
        @endforEach
        @endcan

        @can('relation_show')
        @foreach($relations as $relation)
        E{{ $relation->source_id }} -> E{{ $relation->destination_id }} [label=\"{{ $relation ->name }}\" href=\"#RELATION{{$relation->id}}\"]
  @endforEach
        @endcan
        }
`;

        document.addEventListener('DOMContentLoaded', () => {

            d3.select("#graph").graphviz()
                .addImage("/images/entity.png", "64px", "64px")
                @foreach($entities as $entity)
                @if ($entity->icon_id!==null)
                .addImage("{{ route('admin.documents.show', $entity->icon_id) }}", "64px", "64px")
                @endif
                @endforeach
                .engine("{{ $engine }}")
                .renderDot(dotSrc);
        });
    </script>
@endsection
