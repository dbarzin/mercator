@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Rapport d'architecture
                </div>
                <div class="card-body">
                  <form method="POST" action="/admin/report/cartography" enctype="multipart/form-data" target="_new">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                      <label for="title">{{ trans("cruds.user.fields.granularity") }}</label>
                      <select class="form-control select2 {{ $errors->has('granularity') ? 'is-invalid' : '' }}" name="granularity" id="granularity">
                          <option value="1" {{ auth()->user()->granularity == 1 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_1") }}</option>
                          <option value="2" {{ auth()->user()->granularity == 2 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_2") }}</option>
                          <option value="3" {{ auth()->user()->granularity == 3 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_3") }}</option>
                      </select>
                      <span class="help-block">Niveau de granularité du rapport</span>
                    </div>
                    <div class="form-group">
                        <label for="vues">Vues</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('vues') ? 'is-invalid' : '' }}" name="vues[]" id="vues" multiple>
                                <option value="1">Ecosystème</option>
                                <option value="2">Système d'information</option>
                                <option value="3">Applications</option>
                                <option value="4">Administration</option>
                                <option value="5">Infrastructure logique</option>
                                <option value="6">Infrastructure physique</option>
                        </select>
                        @if($errors->has('processes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('processes') }}
                            </div>
                        @endif
                        <span class="help-block">Vues présentes dans le rapport</span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            Generate
                        </button>
                    </div>
                  </form>
                </div>

              </div>
              <div class="card">

                <div class="card-header">
                    Listes
                </div>
                <div class="card-body">
                  <ul>
                    <li>
                      Liste de toutes les applications par groupe applicatif<br>
                      <a href="/admin/report/applicationsByBlocks" target="_new">Applications par groupe applicatif</a><br><br>
                    </li>
                    <li>
                      Liste serveurs logiques par applications et responsables<br>
                      <a href="/admin/report/logicalServerResp" target="_new">Liste des serveurs logiques</a><br><br>
                    </li>
                    <li>
                      Liste de la configuration des serveurs logiques<br>
                      <a href="/admin/report/logicalServerConfigs" target="_new">Configuration des serveurs logiques</a><br><br>
                    </li>
                    <li>
                      Liste des équipements par site/local<br>
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