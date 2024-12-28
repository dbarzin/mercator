@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table width="100%">
                        <tr>
                            <td width="400">
                                <div class="form-group">
                                    <label for="title">Filtre</label>
                                    <select class="form-control select2" id="filters" multiple>
                                        <option value="1">{{ trans("cruds.report.cartography.ecosystem") }}</option>
                                        <option value="2">{{ trans("cruds.report.cartography.information_system") }}</option>
                                        <option value="3">{{ trans("cruds.report.cartography.applications") }}</option>
                                        <option value="4">{{ trans("cruds.report.cartography.administration") }}</option>
                                        <option value="5">{{ trans("cruds.report.cartography.logical_infrastructure") }}</option>
                                        <option value="9">{{ trans("cruds.flux.title") }}</option>
                                        <option value="6">{{ trans("cruds.report.cartography.physical_infrastructure") }}</option>
                                        <option value="7">{{ trans("cruds.report.cartography.network_infrastructure") }}</option>
                                        <option value="8">{{ trans("cruds.physicalLink.title") }}</option>
                                    </select>
                                    <span class="help-block">{{ trans("cruds.report.explorer.filter_helper") }}</span>
                                </div>
                            </td>
                            <td width=10>
                            </td>
                            <td width="400">
                                <div class="form-group">
                                    <label for="title">{{ trans("cruds.report.explorer.object") }}</label>
                                    <select class="form-control select2" id="node">
                                        <option></option>
                                    </select>
                                    <span class="help-block">{{ trans("cruds.report.explorer.object_helper") }}</span>
                                </div>
                            </td>
                            <td style="text-align: center; vertical-align: middle; width=100px;">
                              <div
                                style="
                                  display: flex;
                                  justify-content: center;
                                  align-items: center;
                                  width: 50px;
                                  height: 50px;
                                  border: 0px solid #007bff;
                                  border-radius: 8px;">
                                      <i
                                      id="node-icon"
                                      class="fas fa-square"
                                      draggable="true"
                                      style="cursor: grab; color: #007bff">
                                    </i>
                                </div>
                            </td>
                            <td style="text-align: right; vertical-align: right;">
                                &nbsp;
                                <a onclick="needSavePNG=true; network.redraw();document.getElementById('canvasImg').click();" href="#"><i class="fas fa-camera-retro"></i>
                                Photo
                                </a>
                                <a id="canvasImg" download="filename"></a>
                            </td>
                        </tr>
                    </table>

                    <div id="app-container" style="display: flex; height: 100vh;">
                      <div id="sidebar" style="width: 50px; background: #ffffff; border-right: 1px solid #ddd; padding: 10px;">

                        <i id="tag" class="fas fa-folder" style="width: 20px; height: 20px; color: #000000;display: block;"></i>
                        <i id="tag" class="fas fa-save" style="width: 20px; height: 20px; color: #000000;display: block;"></i>
                        ---
                        <i id="tag" class="fas fa-rotate-left" style="width: 20px; height: 20px; color: #000000;display: block;"></i>
                        <i id="tag" class="fas fa-rotate-right" style="width: 20px; height: 20px; color: #000000;display: block;"></i>
                        --
                        <i id="tag" class="fas fa-pencil" style="width: 20px; height: 20px; color: #000000;display: block;"></i>
                        <i id="tag" class="fas fa-palette" style="width: 20px; height: 20px; color: #000000;display: block;"></i>
                        ---
                        <i id="tag" class="fas fa-object-group" style="width: 20px; height: 20px; color: #000000;display: block;"></i>
                        <i id="tag" class="fas fa-object-ungroup" style="width: 20px; height: 20px; color: #000000;display: block;"></i>
                        ---
                        <i id="zoom-in-btn" class="fas fa-plus" style="width: 20px; height: 20px; color: #000000;display: block;""></i>
                        <i id="zoom-out-btn" class="fas fa-minus" style="width: 20px; height: 20px; color: #000000;display: block;"></i>

                      </div>
                      <div id="graph-container" style="flex: 1; background: #fff;"></div>
                    </div>

                    <!-- div id="graph-container" style="width: 100%; height: 600px"></div-->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
@vite('resources/css/mapping.css')
@endsection

@section('scripts')
@vite('resources/js/mapping.ts')

<script>

    // initialize select2
    $('.select2').select2();

    // clear selections
    $('#filters').val(null);
    $('#node').val(null);
</script>
@endsection
