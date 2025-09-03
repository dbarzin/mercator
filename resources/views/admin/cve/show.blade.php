@extends('layouts.admin')
@section('content')
<div class="form-group">
    <div class="form-group">
        <a class="btn btn-default" href="{{ route('admin.applications.index') }}">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

<div class="card">


    <div class="card-header">
        CVE List
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-bordered table-striped table-hover datatable">
                <thead>
                    <tr>
                        <th>
                        </th>
                        <th>
                            CVE
                        </th>
                        <th>
                            Description
                        </th>
                        <th>
                            Date Published
                        </th>
                        <th>
                            Date Updated
                        </th>
                        <th>
                            Score
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cves as $cve)
                        <tr>
                            <td>
                            </td>
                            <td nowrap>
                                <a href="https://nvd.nist.gov/vuln/detail/{{ $cve->cveId }}">
                                {{ $cve->cveId }}
                                </a>
                            </td>
                            <td>
                                @if ($cve->title!==null)
                                <b>{{ $cve->title }}</b>
                                <br>
                                @endif
                                {{ $cve->description }}
                            </td>
                            <td>
                                {{ $cve->datePublished }}
                            </td>
                            <td>
                                {{ $cve->dateUpdated }}
                            </td>
                            <td nowrap>
                                @if($cve->baseSeverity=="HIGH")
                                <span class="badge-high">
                                @elseif($cve->baseSeverity=="CRITICAL")
                                <span class="badge-critical">
                                @elseif($cve->baseSeverity=="MEDIUM")
                                <span class="badge-medium">
                                @else
                                <span class="label label-default">
                                @endif
                                {{ $cve->baseScore }}
                                {{ $cve->baseSeverity }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
<script>
@include('partials.datatable', array(
    'id' => '#dataTable',
    'title' => "CVE",
    'URL' => null,
    'canDelete' => false
));
</script>
@endsection
