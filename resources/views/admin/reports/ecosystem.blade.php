@extends('layouts.admin')

@section('title')
    {{ trans('cruds.menu.ecosystem.title') }}
@endsection

@section('content')
<div class="graph-card-sticky">
    <div class="card mb-3">
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
                           style="max-width: 600px;">
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
                                            <option value="{{ $key }}" {{ Session::get('perimeter')==$key? "selected" : "" }}>
                                            {{ $choice }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </td>
                            <td>
                                <select name="entity_type" onchange="this.form.submit()"
                                        class="form-control select2">
                                    <option value="All" {{ Session::get('entity_type')== null ? "selected" : "" }} >{{ trans('cruds.entity.filters.all_types') }}</option>
                                    @foreach ($entityTypes as $type)
                                        <option value="{{ $type }}" {{ Session::get('entity_type')==$type? "selected" : "" }}>
                                        {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                        </tr>
                    </table>
                </div>
                <div id="graph-container">
                    <div class="graphviz" id="graph"></div>
                    <div class="graph-resize-handle"></div>
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
                                />
                                <span>{{ $value }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>{{-- .graph-card-sticky --}}

<div class="report-scroll-area">
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
                            <div class="col">
                                @include('admin.entities._details', [
                                    'entity' => $entity,
                                    'withLink' => true,
                                ])
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
                            <div class="col">
                                @include('admin.relations._details', [
                                    'relation' => $relation,
                                    'withLink' => true,
                                ])
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endcan
</div>{{-- .report-scroll-area --}}
@endsection

@section('scripts')
@vite(['resources/js/graphviz.js'])
<script id="dot-input">
let dotSrc = `
digraph  {
node [shape=none labelloc="b"  width=1 height=1.1]
@can('entity_show')
    @foreach($entities as $entity)
    E{{ $entity->id }} [label="{{ $entity->name }}"  image="{{ $entity->icon_id === null ? '/images/entity.png' : route('admin.documents.show', $entity->icon_id) }}" href="#{{ $entity->getUID() }}"]
    @if (($entity->parentEntity!=null)&&($entities->contains("id",$entity->parentEntity->id)))
    E{{ $entity->parentEntity->id }} -> E{{ $entity->id }}
    @endif
    @endforEach
    @endcan

    @can('relation_show')
    @foreach($relations as $relation)
    E{{ $relation->source_id }} -> E{{ $relation->destination_id }} [label=\"{{ $relation ->name }}\" href=\"#{{ $relation->getUID() }}\"]
    @endforEach
@endcan
}`;

document.addEventListener('graphvizReady', () => {
    const images = [
        { path: "/images/entity.png", width: "64px", height: "64px" },
        @foreach($entities as $entity)
        @if ($entity->icon_id !== null)
        { path: "{{ route('admin.documents.show', $entity->icon_id) }}", width: "64px", height: "64px" },
        @endif
        @endforeach
    ];

    document.getElementById("graph").innerHTML = window.graphviz.layout(
        dotSrc,
        "svg",
        "{{ $engine }}",
        { images: images }
    );
});


</script>
@endsection