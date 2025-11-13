@extends('layouts.admin')
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
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

            @can('zone_admin_access')
                @if ($zones->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans('cruds.zoneAdmin.title') }}
                        </div>

                        <div class="card-body">
                            <p>{{ trans('cruds.zoneAdmin.title') }}</p>
                            <ul>
                                @foreach($zones as $zone)
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <table class="table table-bordered table-striped table-hover table-report">
                                                <thead id="ZONE{{ $zone->id }}">
                                                <th colspan="2">
                                                    <a href="/admin/zone-admins/{{ $zone->id }}">{{ $zone->name }}</a>
                                                </th>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th width="20%">{{ trans('cruds.zoneAdmin.fields.description') }}</th>
                                                    <td>{!! $zone->description !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.zoneAdmin.fields.annuaires') }}</th>
                                                    <td>
                                                        @foreach($zone->annuaires as $annuaire)
                                                            <a href="#ANNUAIRE{{$annuaire->id}}">{{ $annuaire->name }}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.zoneAdmin.fields.forests') }}</th>
                                                    <td>
                                                        @foreach($zone->forestAds as $forest)
                                                            <a href="#FOREST{{$forest->id}}">{{ $forest->name }}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                </tbody>
                                                </tr>
                                            </table>
                                            <br>
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
                            <ul>
                                @foreach($annuaires as $annuaire)
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <table class="table table-bordered table-striped table-hover table-report">
                                                <thead id="ANNUAIRE{{ $annuaire->id }}">
                                                <th colspan="2">
                                                    <a href="/admin/annuaires/{{ $annuaire->id }}">{{ $annuaire->name }}</a>
                                                </th>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th width="20%">{{ trans('cruds.annuaire.fields.description') }}</th>
                                                    <td>{!! $annuaire->description !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.annuaire.fields.solution') }}</th>
                                                    <td>{{ $annuaire->solution }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.annuaire.fields.zone_admin') }}</th>
                                                    <td>
                                                        @if ($annuaire->zone_admin!=null)
                                                            <a href="#ZONE{{$annuaire->zone_admin->id}}">{{$annuaire->zone_admin->name}}</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                </tbody>
                                                </tr>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('forest_ad_access')
                @if ($forests->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans('cruds.forestAd.title') }}
                        </div>

                        <div class="card-body">
                            <p>{{ trans('cruds.forestAd.description') }}</p>
                            <ul>
                                @foreach($forests as $forest)
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <table class="table table-bordered table-striped table-hover table-report">
                                                <thead id="FOREST{{ $forest->id }}">
                                                <th colspan="2">
                                                    <a href="/admin/forest-ads/{{ $forest->id }}">{{ $forest->name }}</a>
                                                </th>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th width="20%">{{ trans('cruds.forestAd.fields.description') }}</th>
                                                    <td>{!! $forest->description !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.forestAd.fields.zone_admin') }}</th>
                                                    <td>
                                                        @if ($forest->zone_admin!=null)
                                                            <a href="#ZONE{{$forest->zone_admin->id}}">{{$forest->zone_admin->name}}</a>
                                                        @endif
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.forestAd.fields.domaines') }}</th>
                                                    <td>
                                                        @foreach($forest->domaines as $domain)
                                                            <a href="#DOMAIN{{$domain->id}}">{{$domain->name}}</a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan

            @can('domaine_ad_access')
                @if ($domains->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans('cruds.domaineAd.title') }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans('cruds.domaineAd.description') }}</p>
                            <ul>
                                @foreach($domains as $domain)
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <table class="table table-bordered table-striped table-hover table-report">
                                                <thead id="DOMAIN{{ $domain->id }}">
                                                <th colspan="2">
                                                    <a href="/admin/domaine-ads/{{ $domain->id }}">{{ $domain->name }}</a>
                                                </th>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th width="20%">{{ trans('cruds.domaineAd.fields.description') }}</th>
                                                    <td>{!! $domain->description !!}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.domaineAd.fields.domain_ctrl_cnt') }}</th>
                                                    <td>{{ $domain->domain_ctrl_cnt }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.domaineAd.fields.user_count') }}</th>
                                                    <td>{{ $domain->user_count }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.domaineAd.fields.machine_count') }}</th>
                                                    <td>{{ $domain->machine_count }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.domaineAd.fields.relation_inter_domaine') }}</th>
                                                    <td>{{ $domain->relation_inter_domaine }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.forestAd.title') }}</th>
                                                    <td>
                                                        @foreach($domain->forestAds as $forestAd)
                                                            <a href="{{ route('admin.forest-ads.show', $forestAd->id) }}">
                                                                {{ $forestAd->name }}
                                                            </a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>{{ trans('cruds.logicalServer.title') }}</th>
                                                    <td>
                                                        @foreach($domain->logicalServers as $logicalServer)
                                                            <a href="{{ route('admin.logical-servers.show', $logicalServer->id) }}">
                                                                {{ $logicalServer->name }}
                                                            </a>
                                                            @if (!$loop->last)
                                                                ,
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan
            @can('admin_user_access')
                @if ($adminUsers->count()>0)
                    <br>
                    <div class="card">
                        <div class="card-header">
                            {{ trans('cruds.adminUser.title') }}
                        </div>
                        <div class="card-body">
                            <p>{{ trans('cruds.adminUser.description') }}</p>
                            <ul>
                                @foreach($adminUsers as $adminUser)
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <table class="table table-bordered table-striped table-hover table-report">
                                                <thead id="USER{{$adminUser->id}}">
                                                <th colspan="4">
                                                    <a href="/admin/admin-users/{{ $adminUser->id }}">{{ $adminUser->user_id }}</a>
                                                </th>
                                                </thead>
                                                <tbody>
                                                <tr>
                                                    <th width="10%">
                                                        {{ trans('cruds.adminUser.fields.firstname') }}
                                                    </th>
                                                    <td width="30%">
                                                        {{ $adminUser->firstname }}
                                                    </td>
                                                    <th width="10%">
                                                        {{ trans('cruds.adminUser.fields.lastname') }}
                                                    </th>
                                                    <td>
                                                        {{ $adminUser->lastname }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        <dt>{{ trans('cruds.adminUser.fields.description') }}</dt>
                                                    </th>
                                                    <td colspan="3">
                                                        {!! $adminUser->description !!}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        {{ trans('cruds.adminUser.fields.domain') }}
                                                    </th>
                                                    <td>
                                                        @if ($adminUser->domain_id !== null)
                                                            <a href="{{ route('admin.domaine-ads.show', $adminUser->domain_id) }}">
                                                                {{ $adminUser->domain->name }}
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <th>
                                                        {{ trans('cruds.adminUser.fields.type') }}
                                                    </th>
                                                    <td>
                                                        {{ $adminUser->type }}
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <th>
                                                        {{ trans('cruds.adminUser.fields.local') }}
                                                    </th>
                                                    <td>
                                                        {{ $adminUser->local }}
                                                    </td>
                                                    <th>
                                                        {{ trans('cruds.adminUser.fields.privileged') }}
                                                    </th>
                                                    <td>
                                                        {{ $adminUser->privileged }}
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <br>
                                        </div>
                                    </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endcan
        </div>
    </div>
@endsection

@section('scripts')
    @vite(['resources/js/d3-viz.js'])

    <script>
        let dotSrc = `
digraph  {
    @foreach($zones as $zone)
        Z{{ $zone->id }} [label="{{ $zone->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/zoneadmin.png" href="#ZONE{{$zone->id}}"]
        @foreach($zone->annuaires as $annuaire)
        Z{{ $zone->id }} -> A{{$annuaire->id}}
        @endforeach
        @foreach($zone->forestAds as $forest)
        Z{{ $zone->id }} -> F{{$forest->id}}
        @endforeach
        @endforEach
        @foreach($annuaires as $annuaire)
        A{{ $annuaire->id }} [label="{{ $annuaire->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/annuaire.png" href="#ANNUAIRE{{$annuaire->id}}"]
    @endforEach
        @foreach($forests as $forest)
        F{{ $forest->id }} [label="{{ $forest->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/ldap.png" href="#FOREST{{$forest->id}}"]
        @foreach($forest->domaines as $domain)
        F{{ $forest->id }} -> D{{ $domain->id }}
        @endforeach
        @endforeach
        @foreach($domains as $domain)
        D{{ $domain->id }} [label="{{ $domain->name }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/domain.png" href="#DOMAIN{{$domain->id}}"]
    @endforeach
        @can('admin_user_access')
        @foreach($adminUsers as $user)
        @if ($user->domain_id!==null)
        U{{$user->id}} [label="{{ $user->user_id }}" shape=none labelloc="b"  width=1 height=1.1 image="/images/user.png" href="#USER{{$user->id}}"]
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
