@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans('cruds.menu.administration.title') }}
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

            @can('zone_admin_access')
            @if ($zones->count()>0)
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
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="ZONE{{ $zone->id }}">
                                        <th colspan="2">
                                            @can('zone_admin_edit')
                                            <a href="/admin/zone-admins/{{ $zone->id }}/edit">{{ $zone->name }}</a>
                                            @else
                                            <a href="/admin/zone-admins/{{ $zone->id }}">{{ $zone->name }}</a>
                                            @endcan
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
                                            @foreach($zone->zoneAdminAnnuaires as $annuaire) 
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
                                            @foreach($zone->zoneAdminForestAds as $forest) 
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
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="ANNUAIRE{{ $annuaire->id }}">
                                        <th colspan="2">
                                            @can('annuaire_edit')
                                            <a href="/admin/annuaires/{{ $annuaire->id }}/edit">{{ $annuaire->name }}</a>
                                            @else
                                            <a href="/admin/annuaires/{{ $annuaire->id }}">{{ $annuaire->name }}</a>
                                            @endcan
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
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="FOREST{{ $forest->id }}">
                                        <th colspan="2">
                                            @can('forest_ad_edit')
                                            <a href="/admin/forest-ads/{{ $forest->id }}/edit">{{ $forest->name }}</a>
                                            @else
                                            <a href="/admin/forest-ads/{{ $forest->id }}">{{ $forest->name }}</a>
                                            @endcan
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
                                <table class="table table-bordered table-striped table-hover">
                                    <thead id="DOMAIN{{ $domain->id }}">
                                        <th colspan="2">
                                            @can('domaine_ad_edit')
                                            <a href="/admin/domaine-ads/{{ $domain->id }}/edit">{{ $domain->name }}</a>
                                            @else
                                            <a href="/admin/domaine-ads/{{ $domain->id }}">{{ $domain->name }}</a>
                                            @endcan
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
                                        <th>{{ trans('cruds.domaineAd.fields.forestAds') }}</th>
                                        <td>
                                            @foreach($domain->domainesForestAds as $forest)
                                                <a href="#FOREST{{$forest->id}}">{{ $forest->name}}</a>
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
    .addImage("/images/zoneadmin.png", "64px", "64px")
    .addImage("/images/annuaire.png", "64px", "64px")
    .addImage("/images/ldap.png", "64px", "64px")
    .addImage("/images/domain.png", "64px", "64px")
    .renderDot("digraph  {\
            <?php  $i=0; ?>\
            @foreach($zones as $zone) \
                Z{{ $zone->id }} [label=\"{{ $zone->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/zoneadmin.png\" href=\"#ZONE{{$zone->id}}\"]\
                @foreach($zone->zoneAdminAnnuaires as $annuaire) \
                    Z{{ $zone->id }} -> A{{$annuaire->id}}\
                @endforeach\
                @foreach($zone->zoneAdminForestAds as $forest) \
                    Z{{ $zone->id }} -> F{{$forest->id}}\
                @endforeach\
            @endforEach\
            @foreach($annuaires as $annuaire) \
                A{{ $annuaire->id }} [label=\"{{ $annuaire->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/annuaire.png\" href=\"#ANNUAIRE{{$annuaire->id}}\"]\
            @endforEach\
            @foreach($forests as $forest)\
                F{{ $forest->id }} [label=\"{{ $forest->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/ldap.png\" href=\"#FOREST{{$forest->id}}\"]\
                @foreach($forest->domaines as $domain) \
                F{{ $forest->id }} -> D{{ $domain->id }} \
                @endforeach\
            @endforeach\
            @foreach($domains as $domain)\
                D{{ $domain->id }} [label=\"{{ $domain->name }}\" shape=none labelloc=\"b\"  width=1 height=1.1 image=\"/images/domain.png\" href=\"#DOMAIN{{$domain->id}}\"]\
            @endforeach\
        }");

</script>
@parent
@endsection