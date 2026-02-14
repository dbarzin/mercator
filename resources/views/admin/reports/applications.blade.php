@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.menu.application.title") }}
                </div>
                <form action="/admin/report/applications">

                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-sm-5">
                            <table class="table table-bordered table-striped table-hover table-select">
                                <tr>
                                    <td style="min-width: 280px;">
                                        {{ trans("cruds.applicationBlock.title") }} :
                                        <select name="applicationBlock" id="applicationBlock"
                                                class="form-control select2"
                                                onchange="this.form.application.value='';this.form.submit()">
                                            <option value="">-- All --</option>
                                            @foreach ($all_applicationBlocks as $applicationBlock)
                                                <option value="{{$applicationBlock->id}}" {{ Session::get('applicationBlock')==$applicationBlock->id ? "selected" : "" }}>{{ $applicationBlock->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="min-width: 280px;">
                                        {{ trans("cruds.application.title") }} :
                                        <select name="application" id="application" class="form-control select2"
                                                onchange="this.form.submit()">
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
                                        >
                                        <span>{{ $value }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            @can('application_block_access')
                @if ($applicationBlocks->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.applicationBlock.title") }}
                        </div>

                        <div class="card-body">
                            <p>{{ trans("cruds.applicationBlock.description") }}</p>
                            @foreach($applicationBlocks as $applicationBlock)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.applicationBlocks._details', [
                                            'applicationBlock' => $applicationBlock,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('application_access')
                @if ($applications->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.application.title") }}
                        </div>

                        <div class="card-body">
                            <p>{{ trans("cruds.application.description") }}</p>
                            @foreach($applications as $application)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.applications._details', [
                                            'application' => $application,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('application_service_access')
                @if ($applicationServices->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.applicationService.title") }}
                        </div>

                        <div class="card-body">
                            <p>{{ trans("cruds.applicationService.description") }}</p>
                            @foreach($applicationServices as $applicationService)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.applicationServices._details', [
                                            'applicationService' => $applicationService,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('application_module_access')
                @if ($applicationModules->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.applicationModule.title") }}
                        </div>

                        <div class="card-body">
                            <p>{{ trans("cruds.applicationModule.description") }}</p>
                            @foreach($applicationModules as $applicationModule)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.applicationModules._details', [
                                            'applicationModule' => $applicationModule,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('database_access')
                @if ($databases->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.database.title") }}
                        </div>

                        <div class="card-body">
                            <p>{{ trans("cruds.database.description") }}</p>
                            @foreach($databases as $database)
                                <div class="row">
                                    <div class="col">
                                        @include('admin.databases._details', [
                                            'database' => $database,
                                            'withLink' => true,
                                        ])
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('flux_access')
                @if ($fluxes->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans("cruds.flux.title") }}
                        </div>

                        <div class="card-body">
                            <p>{{ trans("cruds.flux.description") }}</p>
                            @foreach($fluxes as $flux)
                                <div class="row">
                                    <div class="col">

                                        @include('admin.fluxes._details', [
                                            'flux' => $flux,
                                            'withLink' => true,
                                        ])
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
@vite(['resources/js/d3-viz.js'])
<script>
const dotSrc = `digraph  {
@can('application_block_access')
    @foreach($applicationBlocks as $ab)
    AB{{ $ab->id }} [label="{{ $ab->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/applicationblock.png" href="#{{$ab->getUID()}}"]
    @endforeach
@endcan

@can('application_access')
    @foreach($applications as $application)
    A{{ $application->id }} [label="{{ $application->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $application->icon_id === null ? '/images/application.png' : route('admin.documents.show', $application->icon_id) }}" href="#{{$application->getUID()}}"]
    @can('application_service_access')
    @foreach($application->services as $service)
        A{{ $application->id }} -> AS{{ $service->id}}
    @endforeach
    @endcan
    @can('database_access')
    @foreach($application->databases as $database)
        A{{ $application->id }} -> DB{{ $database->id}}
    @endforeach
    @endcan
    @can('application_block_access')
    @if ($application->application_block_id!=null)
        AB{{ $application->application_block_id }} -> A{{ $application->id}}
    @endif
    @endcan
    @endforeach
@endcan
@can('application_service_access')
    @foreach($applicationServices as $service)
    AS{{ $service->id }} [label="{{ $service->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/applicationservice.png" href="#{{$service->getUID()}}"]
    @can('application_module_access')
    @foreach($service->modules as $module)
        AS{{ $service->id }} -> M{{$module->id}}
    @endforeach
    @endcan
    @endforeach
@endcan
@can('application_module_access')
    @foreach($applicationModules as $module)
    M{{ $module->id }} [label="{{ $module->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/applicationmodule.png" href="#{{$module->getUID()}}"]
    @endforeach
@endcan
@can('database_access')
    @foreach($databases as $database)
    DB{{ $database->id }} [label="{{ $database->name }}" shape=none labelloc="b"  width=1 height=1.1 image="{{ $database->icon_id === null ? '/images/database.png' : route('admin.documents.show', $database->icon_id) }}" href="#{{$database->getUID()}}"]
    @endforeach
@endcan
}`;

document.addEventListener('DOMContentLoaded', () => {
    d3.select("#graph").graphviz()
        .addImage("/images/applicationblock.png", "64px", "64px")
        .addImage("/images/application.png", "64px", "64px")
        .addImage("/images/applicationservice.png", "64px", "64px")
        .addImage("/images/applicationmodule.png", "64px", "64px")
        .addImage("/images/database.png", "64px", "64px")
        .addImage("/images/applicationblock.png", "64px", "64px")
        @can('application_access')
        @foreach($applications as $application)
        @if ($application->icon_id!==null)
        .addImage("{{ route('admin.documents.show', $application->icon_id) }}", "64px", "64px")
        @endif
        @endforeach
        @endcan
        @can('database_access')
        @foreach($databases as $database)
        @if ($database->icon_id!==null)
        .addImage("{{ route('admin.documents.show', $database->icon_id) }}", "64px", "64px")
        @endif
        @endforeach
        @endcan
        .engine("{{ $engine }}")
        .renderDot(dotSrc);
});
</script>
@endsection
