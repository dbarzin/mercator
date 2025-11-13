@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <form action="/admin/report/gdpr">
                    <div class="card-header">
                        {{ trans('cruds.menu.gdpr.title') }}
                    </div>

                    <div class="card-body">
                        @if(session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                        <div class="col-sm-6">
                            <table class="table table-bordered table-striped"
                                   style="max-width: 600px;">
                                <tr>
                                    <td style="min-width: 280px">
                                        {{ trans('cruds.macroProcessus.title') }} :
                                        <select name="macroprocess"
                                                onchange="this.form.process.value='';this.form.submit()"
                                                class="form-control select2">
                                            <option value="">-- All --</option>
                                            @foreach ($all_macroprocess as $macroprocess)
                                                <option value="{{$macroprocess->id}}" {{ Session::get('macroprocess')==$macroprocess->id ? "selected" : "" }}>{{ $macroprocess->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td style="width: 300px">
                                        {{ trans('cruds.process.title') }} :
                                        <select name="process" onchange="this.form.submit()"
                                                class="form-control select2">
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

            @can('data_processing_access')

                <div class="card">
                    <div class="card-header">
                        {{ trans('cruds.dataProcessing.title') }} :
                    </div>
                    <div class="card-body">
                        <p>{{ trans('cruds.dataProcessing.description') }}</p>
                        @foreach($dataProcessings as $dataProcessing)
                            <div class="row">
                                <div class="col-sm-6">
                                    <table class="table table-bordered table-striped table-hover table-report">
                                        <thead id="DATAPROCESSING{{ $dataProcessing->id }}">
                                        <th colspan="2">
                                            <a href="/admin/data-processings/{{ $dataProcessing->id }}">{{ $dataProcessing->name }}</a>
                                        </th>
                                        </thead>
                                        <tbody>
                                        <tr>
                                            <th width="20%">{{ trans('cruds.dataProcessing.fields.legal_basis') }}</th>
                                            <td>{{ $dataProcessing->legal_basis }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.dataProcessing.fields.description') }}</th>
                                            <td>{!! $dataProcessing->description !!}</td>
                                        </tr>
                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.responsible') }}
                                            </th>
                                            <td>
                                                {!! $dataProcessing->responsible !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.purpose') }}
                                            </th>
                                            <td>
                                                {!! $dataProcessing->purpose !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.categories') }}
                                            </th>
                                            <td>
                                                {!! $dataProcessing->categories !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.recipients') }}
                                            </th>
                                            <td>
                                                {!! $dataProcessing->recipients !!}
                                            </td>
                                        </tr>


                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.transfert') }}
                                            </th>
                                            <td>
                                                {!! $dataProcessing->transfert !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.retention') }}
                                            </th>
                                            <td>
                                                {!! $dataProcessing->retention !!}
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.processes') }}
                                            </th>
                                            <td>
                                                @foreach($dataProcessing->processes as $process)
                                                    <a href="{{ route('admin.processes.show', $process->id) }}">{{ $process->name }}</a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.applications') }}
                                            </th>
                                            <td>
                                                @foreach($dataProcessing->applications as $application)
                                                    <a href="{{ route('admin.applications.show', $application->id) }}">{{ $application->name }}</a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.information') }}
                                            </th>
                                            <td>
                                                @foreach($dataProcessing->informations as $information)
                                                    <a href="{{ route('admin.information.show', $information->id) }}">{{ $information->name }}</a>
                                                    @if (!$loop->last)
                                                        ,
                                                    @endif
                                                @endforeach
                                            </td>
                                        </tr>

                                        <tr>
                                            <th>
                                                {{ trans('cruds.dataProcessing.fields.documents') }}
                                            </th>
                                            <td>
                                                @foreach($dataProcessing->documents as $document)
                                                    <a href="{{ route('admin.documents.show', $document->id) }}">{{ $document->filename }}</a>
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

            @endcan
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/d3-viz.js'])
    <script>
        let dotSrc = `
digraph  {
    @foreach($macroProcessuses as $macroProcess)
        MP{{ $macroProcess->id }} [label="{{ $macroProcess->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/macroprocess.png"  href="#MACROPROCESS{{ $macroProcess->id }}"]
    @endforeach
        @foreach($processes as $process)
        P{{ $process->id }} [label="{{ $process->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/process.png"  href="#PROCESS{{ $process->id }}"]
        MP{{ $process->macroprocess_id }} -> P{{ $process->id }}
        @foreach($process->dataProcesses as $dp)
        P{{ $process->id }} -> DP{{ $dp->id}}
        @endforeach

        @endforeach
        @foreach($dataProcessings as $dp)
        DP{{ $dp->id }} [label="{{ $dp->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/dataprocessing.png"  href="#DATAPROCESSING{{ $dp->id }}"]
        @foreach($dp->applications as $app)
        DP{{ $dp->id }} -> APP{{ $app->id }}
        @endforeach
        @endforeach
        @foreach($applications as $app)
        APP{{ $app->id }} [label="{{ $app->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/application.png"]
    @endforeach
        }`;

        document.addEventListener('DOMContentLoaded', () => {
            d3.select("#graph").graphviz()
                .addImage("/images/macroprocess.png", "64px", "64px")
                .addImage("/images/process.png", "64px", "64px")
                .addImage("/images/dataprocessing.png", "64px", "64px")
                .addImage("/images/application.png", "64px", "64px")
                .engine("{{ $engine }}")
                .renderDot(dotSrc);
        });
    </script>
    @parent
@endsection
