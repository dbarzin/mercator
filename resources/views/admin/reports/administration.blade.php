@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Vue de l'administration
                </div>

                <div class="card-body">
                    @if(session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div id="graph" style="text-align: center;"></div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Zones d'administration
                </div>

                <div class="card-body">
                    <p>Ensemble de ressources (personnes, données, équipements) sous la responsabilité d’un (ou plusieurs) administrateur(s).</p>
                    <ul>
                        @foreach($zones as $zone)
                            <li id="ZONE{{ $zone->id }}">
                                <a href="/admin/zone-admins/{{ $zone->id }}/edit">{{ $zone->name }}</a><br>
                                <b>Description</b> : <br>
                                {!! $zone->description !!}
                                <b>Annuaires</b> : 
                                @foreach($zone->zoneAdminAnnuaires as $annuaire) 
                                    <a href="#ANNUAIRE{{$annuaire->id}}">{{ $annuaire->name }}</a>
                                @endforeach
                                <br>
                                <b>Forêt Active Directory / Arborescence LDAP</b> :                            
                                @foreach($zone->zoneAdminForestAds as $forest) 
                                    <a href="#FOREST{{$forest->id}}">{{ $forest->name }}</a>
                                @endforeach
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Service d’annuaire d’administration
                </div>

                <div class="card-body">
                    <p>Applicatif regroupant les données sur les utilisateurs ou équipements informatiques de l’entreprise et permettant leur administration.</p>
                    <ul>
                        @foreach($annuaires as $annuaire)
                            <li id="ANNUAIRE{{ $annuaire->id }}">
                                <a href="/admin/annuaires/{{ $annuaire->id }}/edit">{{ $annuaire->name }}</a><br>
                                <b>Description</b> : <br>
                                {!! $annuaire->description !!}
                                <b>Solution</b> : {{ $annuaire->solution }} <br>
                                <b>Zone d'administration</b> : 
                                @if ($annuaire->zone_admin!=null)
                                    <a href="#ZONE{{$annuaire->zone_admin->id}}">{{$annuaire->zone_admin->name}}</a>
                                @endif
                                <br><br>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Forêt Active Directory / Arborescence LDAP
                </div>

                <div class="card-body">
                    <p>Regroupement organisé de domaines Active Directory/LDAP.</p>
                    <ul>
                        @foreach($forests as $forest)
                            <li id="FOREST{{ $forest->id }}">
                                <a href="/admin/forest-ads/{{ $forest->id }}/edit">{{ $forest->name }}</a><br>
                                <b>Description</b> : <br>
                                {!! $forest->description !!}
                                
                                <b>Zone d'administration</b> : 
                                @if ($forest->zone_admin!=null)
                                    <a href="#ZONE{{$forest->zone_admin->id}}">{{$forest->zone_admin->name}}</a>
                                @endif
                                <br>
                                <b>Domaines</b> : 
                                @foreach($forest->domaines as $domain) 
                                    <a href="#DOMAIN{{$domain->id}}">{{$domain->name}}</a>
                                @endforeach
                                <br><br>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Domaines
                </div>

                <div class="card-body">
                    <p>Ensemble d’éléments (membres, ressources) régis par une même politique de sécurité.</p>
                    <ul>
                        @foreach($domains as $domain)
                            <li id="DOMAIN{{ $domain->id }}">
                                <a href="/admin/domaine-ads/{{ $domain->id }}/edit">{{ $domain->name }}</a><br>
                                <b>Description</b> : <br>
                                {!! $domain->description !!}
                                <b>Nombre de controleurs de domaine</b> : {{ $domain->domain_ctrl_cnt }}<br>
                                <b>Nombre de comptes utilisateurs rattachés</b> : {{ $domain->user_count }}<br>
                                <b>Nombre de machines rattachées</b> : {{ $domain->machine_count }}<br>
                                <b>Relations inter-domaines</b> : {{ $domain->relation_inter_domaine }}<br>
                                <b>Forêt Active Directory / Arborescence LDAP</b> : 
                                @foreach($domain->domainesForestAds as $forest)
                                    <a href="#FOREST{{$forest->id}}">{{ $forest->name}}</a>
                                @endforeach
                                <br><br>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>
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