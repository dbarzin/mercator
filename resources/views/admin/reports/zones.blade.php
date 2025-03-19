@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Zones réseau
                </div>
                <div class="card-body">
                    <div id="graph" class="graphviz"></div>
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
<!-- colors -->
<script src="/js/chartjs-plugin-colorschemes.js"></script>

<script>

d3.select("#graph").graphviz()
    .engine("osage")
    .renderDot(`digraph  {
            fontname=Arial
<?php
    $tableau20 = array (
             "#1F77B4", "#AEC7E8", "#FF7F0E", "#FFBB78",
             "#2CA02C", "#98DF8A", "#D62728", "#FF9896",
             "#9467BD", "#C5B0D5", "#8C5694", "#C49C94",
             "#E377C2", "#F7B6D2", "#7F7F7F", "#C7C7C7",
             "#BCBD22", "#DBDB8D", "#17BECF", "#9EDAE5");
    $idColor = 0;
    $colors = array();
?>
    @foreach($subnetworks as $subnet)
<?php
    if (in_array($subnet->name,$colors))
        $color = $colors[$subnet->name];
    else {
        $color = $tableau20[$idColor++ % 20];
        $colors[$subnet->name] = $color;
    }
?>
        subgraph cluster_{{ crc32($subnet->zone) }} {
            label = "{{ $subnet->zone }}"
            style="rounded,filled"
            fontcolor=white color="{{ $color }}"
            edge [style=invis]

            "{{ $subnet->name}}" [
            shape=box
            fontsize=10
            color=black fillcolor=white
            style="filled, border"
            label=<
              <table width='80' border='0' cellborder='0' cellspacing='0'>
                <tr>
                    <td width='60' align='left'>{{ $subnet->name }}</td>
			    <td width='20' align='left'><font color='green'>{{ $subnet->vlan->name ?? "" }}</font></td>
                </tr>
                <tr><td colspan='2' align='left'>{{ explode('/',$subnet->address)[0] }}/<font color='red'>{{ explode('/',$subnet->address)[1] }}</font></td></tr>
                <tr><td colspan='2' align='left'>{{ $subnet->default_gateway }}</td></tr>
              </table>
            >];
        }
     @endforEach
    }`);
</script>
@parent
@endsection
