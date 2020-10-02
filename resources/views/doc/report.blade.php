@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Reporting
                </div>
                <div class="card-body">
                  <ul>
                    <li>
                      <a href="/admin/report/applicationsByBlocks" target="_new">Applications par groupe applicatif</a><br><br>
                    </li>
                    <li>
                      <a href="/admin/report/logicalServerConfigs" target="_new">Configuration des serveurs</a><br><br>
                    </li>
                    <li>
                      <a href="/admin/report/physicalInventory" target="_new">Inventaire de l'infrastructure physique</a><br><br>
                    </li>
                  </ul>

                </div>
              </div>
          </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection