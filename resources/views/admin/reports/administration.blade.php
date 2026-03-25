@extends('layouts.admin')

@section('title')
    {{ trans('cruds.menu.administration.title') }}
@endsection

@section('content')
<div class="graph-card-sticky">
    <div class="card mb-3">
        <div class="card-header">
            {{ trans('cruds.menu.administration.title') }}
        </div>
        <form action="/admin/report/administration">

            <div class="card-body">
                @if(session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <div id="graph-container">
                    <div class="graphviz" id="graph"></div>
                    <div class="graph-resize-handle"></div>
                </div>
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
        </form>
    </div>
</div>

<div class="report-scroll-area">
    @can('zone_admin_access')
        @if ($zones->count()>0)
            <br>
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.zoneAdmin.title') }}
                </div>

                <div class="card-body">
                    <p>{{ trans('cruds.zoneAdmin.title') }}</p>
                        @foreach($zones as $zoneAdmin)
                            <div class="row">
                                <div class="col">
                                    @include('admin.zoneAdmins._details', [
                                        'zoneAdmin' => $zoneAdmin,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endcan

    @can('annuaire_access')
        @if ($annuaires->count()>0)
            <br>
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.annuaire.title') }}
                </div>

                <div class="card-body">
                    <p>{{ trans('cruds.annuaire.description') }}</p>
                        @foreach($annuaires as $annuaire)
                            <div class="row">
                                <div class="col">
                                    @include('admin.annuaires._details', [
                                        'annuaire' => $annuaire,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endcan

    @can('forest_ad_access')
        @if ($forests->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.forestAd.title') }}
                </div>

                <div class="card-body">
                    <p>{{ trans('cruds.forestAd.description') }}</p>
                        @foreach($forests as $forestAd)
                            <div class="row">
                                <div class="col">
                                    @include('admin.forestAds._details', [
                                        'forestAd' => $forestAd,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endcan

    @can('domaine_ad_access')
        @if ($domains->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.domaineAd.title') }}
                </div>
                <div class="card-body">
                    <p>{{ trans('cruds.domaineAd.description') }}</p>
                        @foreach($domains as $domaineAd)
                            <div class="row">
                                <div class="col">
                                    @include('admin.domaineAds._details', [
                                        'domaineAd' => $domaineAd,
                                        'withLink' => true,
                                    ])
                                </div>
                            </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endcan
    @can('admin_user_access')
        @if ($adminUsers->count()>0)
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.adminUser.title') }}
                </div>
                <div class="card-body">
                    <p>{{ trans('cruds.adminUser.description') }}</p>
                        @foreach($adminUsers as $adminUser)
                            <div class="row">
                                <div class="col">
                                    @include('admin.adminUser._details', [
                                        'adminUser' => $adminUser,
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
@endsection

@section('scripts')
@vite(['resources/js/d3-viz.js'])

<script>
let dotSrc = `
digraph  {
@foreach($zones as $zone)
Z{{ $zone->id }} [label="{{ $zone->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/zoneadmin.png" href="#{{$zone->getUID()}}"]
@foreach($zone->annuaires as $annuaire)
Z{{ $zone->id }} -> A{{$annuaire->id}}
@endforeach
@foreach($zone->forestAds as $forest)
Z{{ $zone->id }} -> F{{$forest->id}}
@endforeach
@endforEach
@foreach($annuaires as $annuaire)
A{{ $annuaire->id }} [label="{{ $annuaire->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/annuaire.png" href="#{{$annuaire->getUID()}}"]
@endforEach
@foreach($forests as $forest)
F{{ $forest->id }} [label="{{ $forest->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/ldap.png" href="#{{$forest->getUID()}}"]
@foreach($forest->domaines as $domain)
F{{ $forest->id }} -> D{{ $domain->id }}
@endforeach
@endforeach
@foreach($domains as $domain)
D{{ $domain->id }} [label="{{ $domain->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/domain.png" href="#{{$domain->getUID()}}"]
@endforeach
@can('admin_user_access')
@foreach($adminUsers as $user)
@if ($user->domain_id!==null)
U{{$user->id}} [label="{{ $user->user_id }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/user.png" href="#{{$user->getUID()}}"]
D{{$user->domain_id}} -> U{{$user->id}}
@endif
@endforeach
@endcan
}`;

document.addEventListener('DOMContentLoaded', () => {
    d3.select("#graph").graphviz()
        .addImage("/images/zoneadmin.png", "64px", "64px")
        .addImage("/images/annuaire.png", "64px", "64px")
        .addImage("/images/ldap.png", "64px", "64px")
        .addImage("/images/domain.png", "64px", "64px")
        .addImage("/images/user.png", "64px", "64px")
        .engine("{{ $engine }}")
        .renderDot(dotSrc);
});
</script>
@parent
@endsection
