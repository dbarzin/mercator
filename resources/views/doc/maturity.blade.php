@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Cartographie
                </div>
                <div class="card-body">

                <div class="row">
                  <div class="col-8">

                      <p>Le tableau ci-dessous présente les informations collectées pour chaque niveau  de maturité.</p>


                      <table class="table table-bordered table-striped table-hover datatable datatable-Entity">
                          <thead>
                            <tr>
                              <th rowspan=2><center>Données / Attributs concenés</center></th>
                              <th colspan=2><center>Démarche de cartographie orientée sur la sécurité numérique</center></th>
                              <th><center>Démarche globale de cartographie</center></th>
                            </tr>
                            <tr>
                              <th><center>Maturité de niveau 1</center></th>
                              <th><center>Maturité de niveau 2</center></th>
                              <th><center>Maturité de niveau 3</center></th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td colspan=5><center><a href="/admin/report/ecosystem">Vue de l’écosystème</a></center></td>
                            </tr>
                            <tr>
                              <td>Granularité 1</td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>                        
                              <td>Granularité 2</td>
                              <td></td>
                              <td></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>
                              <td colspan=5><center><a href="/admin/report/information_system">Vue métier du système</a></center></td>
                            </tr>
                            <tr>
                              <td>Granularité 1</td>
                              <td><center></center></td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>                        
                              <td>Granularité 2</td>
                              <td></td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>                        
                              <td>Granularité 3</td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>
                              <td colspan=5><center><a href="/admin/report/applications">Vue des applications</a></center></td>
                            </tr>
                            <tr>
                              <td>Granularité 1</td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>                        
                              <td>Granularité 2</td>
                              <td></td>
                              <td></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>
                              <td colspan=5><center><a href="/admin/report/administration">Vue de l'administration</a></center></td>
                            </tr>
                            <tr>
                              <td>Granularité 1</td>
                              <td><center></center></td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>
                              <td colspan=5><center><a href="/admin/report/logical_infrastructure">Vue des infrastructures logiques</a></center></td>
                            </tr>
                            <tr>
                              <td>Granularité 1</td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>                        
                              <td>Granularité 2</td>
                              <td></td>
                              <td></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>
                              <td colspan=5><center><a href="/admin/report/physical_infrastructure">Vue des infrastructures physiques</a></center></td>
                            </tr>
                            <tr>
                              <td>Granularité 1</td>
                              <td><center></center></td>
                              <td><center>&bull;</center></td>
                              <td><center>&bull;</center></td>
                            </tr>
                            <tr>                        
                              <td>Granularité 2</td>
                              <td></td>
                              <td></td>
                              <td><center>&bull;</center></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="col-4">
                      </div>

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