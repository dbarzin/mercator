@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">

            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.report.cartography.title") }}
                </div>
                <div class="card-body">
                  <form method="POST" action="{{ route('admin.report.cartography') }}" enctype="multipart/form-data" target="_new">
                    @method('PUT')
                    @csrf
                    <div class="form-group">
                      <label for="title">{{ trans("cruds.report.cartography.granularity") }}</label>
                      <select class="form-control select2 {{ $errors->has('granularity') ? 'is-invalid' : '' }}" name="granularity" id="granularity">
                          <option value="1" {{ auth()->user()->granularity == 1 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_1") }}</option>
                          <option value="2" {{ auth()->user()->granularity == 2 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_2") }}</option>
                          <option value="3" {{ auth()->user()->granularity == 3 ? 'selected' : '' }}>{{ trans("cruds.user.fields.granularity_3") }}</option>
                      </select>
                      <span class="help-block">{{ trans("cruds.report.cartography.granularity_helper") }}</span>
                    </div>
                    <div class="form-group">
                        <label for="vues">{{ trans("cruds.report.cartography.views") }}</label>
                        <div style="padding-bottom: 4px">
                            <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                            <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                        </div>
                        <select class="form-control select2 {{ $errors->has('vues') ? 'is-invalid' : '' }}" name="vues[]" id="vues" multiple>
                                <option value="1">{{ trans("cruds.report.cartography.ecosystem") }}</option>
                                <option value="2">{{ trans("cruds.report.cartography.information_system") }}</option>
                                <option value="3">{{ trans("cruds.report.cartography.applications") }}</option>
                                <option value="4">{{ trans("cruds.report.cartography.administration") }}</option>
                                <option value="5">{{ trans("cruds.report.cartography.logical_infrastructure") }}</option>
                                <option value="6">{{ trans("cruds.report.cartography.physical_infrastructure") }}</option>
                        </select>
                        @if($errors->has('processes'))
                            <div class="invalid-feedback">
                                {{ $errors->first('processes') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans("cruds.report.cartography.views_helper") }}</span>
                    </div>

                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans ("global.create") }}
                        </button>
                    </div>
                  </form>
                </div>
              </div>

            </div>
        </div>
        <div class="row">

        <div class="col-lg-6">

              <div class="card">
                <div class="card-header">
                    {{ trans("cruds.report.lists.title") }}
                </div>
                <div class="card-body">
                  <ul>
                    <li>
                        <a href="{{ route('admin.report.entities') }}" target="_new">{{ trans("cruds.report.lists.entities") }}</a>
                        <br>
                        {{ trans("cruds.report.lists.entities_helper") }}
                        <br>                    
                        <br>
                    </li>
                    <li>
                        <a href="{{ route('admin.report.applicationsByBlocks') }}" target="_new">{{ trans("cruds.report.lists.applications") }}</a>
                        <br>
                        {{ trans("cruds.report.lists.applications_helper") }}
                        <br><br>
                    </li>
                    <li>
                        <a href="{{ route('admin.report.logicalServers') }}" target="_new">{{ trans("cruds.report.lists.logical_servers") }}</a><br>
                        {{ trans("cruds.report.lists.logical_servers_helper") }}
                        <br><br>
                    </li>

                    <li>
                        <a href="{{ route('admin.report.securityNeeds') }}" target="_new">{{ trans("cruds.report.lists.security_needs") }}</a>
                        <br>
                        {{ trans("cruds.report.lists.security_needs_helper") }}
                        <br><br>
                    </li>

                    <li>
                        <a href="{{ route('admin.report.vlans') }}" target="_new">{{ trans("cruds.report.lists.vlans") }}</a>                      
                        <br>
                        {{ trans("cruds.report.lists.vlans_helper") }}
                        <br><br>
                    </li>

                    <li>
                        <a href="{{ route('admin.report.logicalServerConfigs') }}" target="_new">{{ trans("cruds.report.lists.logical_server_configurations") }}</a>                      
                        <br>
                        {{ trans("cruds.report.lists.logical_server_configurations_helper") }}
                        <br><br>
                    </li>
                    <li>
                        <a href="{{ route('admin.report.externalAccess') }}" target="_new">{{ trans("cruds.report.lists.external_access") }}</a>
                        <br>
                        {{ trans("cruds.report.lists.external_access_helper") }}
                        <br><br>
                    </li>
                    <li>
                        <a href="{{ route('admin.report.physicalInventory') }}" target="_new">{{ trans("cruds.report.lists.physical_inventory") }}</a>
                        <br>
                        {{ trans("cruds.report.lists.physical_inventory_helper") }}
                        <br><br>
                    </li>
                    <li>
                        <a href="{{ route('admin.report.workstations') }}" target="_new">{{ trans("cruds.report.lists.workstation_inventory") }}</a>
                        <br>
                        {{ trans("cruds.report.lists.workstation_inventory_helper") }}
                        <br><br>
                    </li>
                  </ul>
                </div>
              </div>

          </div>

        <div class="col-lg-6">
            @can('gdpr_access')
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.report.lists.gdpr") }}
                </div>
                <div class="card-body">
                    <ul>
                        <li>
                            <a href="/admin/report/activityReport" target="_new">{{ trans("cruds.report.lists.register_report") }}</a>
                            <br>
                            {{ trans("cruds.report.lists.register_report") }}
                            <br>                    
                            <br>
                        </li>
                        <li>
                            <a href="/admin/report/activityList" target="_new">{{ trans("cruds.report.lists.register_list") }}</a>
                            <br>
                            {{ trans("cruds.report.lists.register_list_helper") }}
                            <br>                    
                            <br>
                        </li>
                    </ul>
                </div>
            </div>
            @endcan

            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.report.lists.audit") }}
                </div>
                <div class="card-body">
                    <ul>
                        <li>
                            <a href="/admin/audit/maturity" target="_new">{{ trans("cruds.report.lists.maturity") }}</a>
                            <br>
                            {{ trans("cruds.report.lists.maturity_helper") }}
                            <br>                    
                            <br>
                        </li>
                    <li>
                        <a href="/admin/audit/changes" target="_new">{{ trans("cruds.report.lists.changes") }}</a>
                        <br>
                        {{ trans("cruds.report.lists.changes_helper") }}
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

          </div>
        </div>
        <br><br><br>
    </div>
</div>
@endsection

@section('scripts')
@parent
@endsection