@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.menu.gdpr.title') }}
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (auth()->user()->granularity>=2)
                    <div class="col-sm-5">
                        <form action="/admin/report/gdpr">
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <td>
                                        {{ trans('cruds.macroProcessus.title') }} :
                                        <select name="macroprocess" onchange="this.form.process.value='';this.form.submit()">
                                            <option value="">-- All --</option>
                                            @foreach ($all_macroprocess as $macroprocess)
                                                <option value="{{$macroprocess->id}}" {{ Session::get('macroprocess')==$macroprocess->id ? "selected" : "" }}>{{ $macroprocess->name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>
                                        {{ trans('cruds.process.title') }} :
                                        <select name="process" onchange="this.form.submit()">
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
                        </form>
                    </div>
                    @endif
                    <div id="graph"></div>
                </div>
            </div>

            @can('data_processing_register_access')

            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.dataProcessing.title') }} :
                </div>
                <div class="card-body">
                    <p>{{ trans('cruds.dataProcessing.description') }}</p>
                      @foreach($dataProcessings as $dataProcessing)
                      <div class="row">
                        <div class="col-sm-6">
                        <table class="table table-bordered table-striped table-hover">
                            <thead id="DATAPROCESSING{{ $dataProcessing->id }}">
                                <th colspan="2">
                                    <a href="/admin/data-processings/{{ $dataProcessing->id }}">{{ $dataProcessing->name }}</a>
                                </th>
                            </thead>
                            <tbody>
                                <tr>
                                    <td width="20%"><b>{{ trans('cruds.macroProcessus.fields.description') }}</b></td>
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

d3.select("#graph").graphviz()
    .addImage("/images/macroprocess.png", "64px", "64px")
    .addImage("/images/process.png", "64px", "64px")
    .addImage("/images/dataprocessing.png", "64px", "64px")
    .addImage("/images/application.png", "64px", "64px")
    .renderDot(dotSrc);
</script>
@parent
@endsection
