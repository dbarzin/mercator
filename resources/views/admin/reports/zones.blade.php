@extends('layouts.admin')

@section('title')
    Zones réseau
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        Zones réseau
    </div>
    <div class="card-body">
        <div id="graph" class="graphviz"></div>
    </div>
</div>
@endsection

@section('scripts')
@vite(['resources/js/graphviz.js'])

@php
$tableau20 = [
    "#1F77B4", "#AEC7E8", "#FF7F0E", "#FFBB78",
    "#2CA02C", "#98DF8A", "#D62728", "#FF9896",
    "#9467BD", "#C5B0D5", "#8C5694", "#C49C94",
    "#E377C2", "#F7B6D2", "#7F7F7F", "#C7C7C7",
    "#BCBD22", "#DBDB8D", "#17BECF", "#9EDAE5",
];
$idColor = 0;
$colors  = [];

$dot  = "digraph {\n";
$dot .= "    fontname=Arial\n";

foreach ($subnetworks as $subnet) {
    if (array_key_exists($subnet->name, $colors)) {
        $color = $colors[$subnet->name];
    } else {
        $color = $tableau20[$idColor++ % 20];
        $colors[$subnet->name] = $color;
    }

    $clusterId = abs(crc32($subnet->zone));   // crc32 peut être négatif
    $zone      = addcslashes($subnet->zone,  '"\\');
    $name      = addcslashes($subnet->name,  '"\\');
    $vlanName  = htmlspecialchars($subnet->vlan->name ?? '', ENT_XML1);
    $parts     = explode('/', $subnet->address ?? '/');
    $addrHost  = htmlspecialchars($parts[0],       ENT_XML1);
    $addrMask  = htmlspecialchars($parts[1] ?? '', ENT_XML1);
    $gateway   = htmlspecialchars($subnet->default_gateway ?? '', ENT_XML1);
    $nameHtml  = htmlspecialchars($subnet->name, ENT_XML1);

    $dot .= "    subgraph cluster_{$clusterId} {\n";
    $dot .= "        cluster=true\n";
    $dot .= "        label=\"{$zone}\"\n";
    $dot .= "        style=\"rounded,filled\"\n";
    $dot .= "        fontcolor=white color=\"{$color}\"\n";
    $dot .= "        edge [style=invis]\n\n";
    $dot .= "        \"{$name}\" [\n";
    $dot .= "            shape=box\n";
    $dot .= "            fontsize=10\n";
    $dot .= "            color=black fillcolor=white\n";
    $dot .= "            style=\"filled,rounded\"\n";
    $dot .= "            label=<\n";
    $dot .= "              <table width=\"80\" border=\"0\" cellborder=\"0\" cellspacing=\"0\">\n";
    $dot .= "                <tr>\n";
    $dot .= "                  <td width=\"60\" align=\"left\">{$nameHtml}</td>\n";
    $dot .= "                  <td width=\"20\" align=\"left\"><font color=\"green\">{$vlanName}</font></td>\n";
    $dot .= "                </tr>\n";
    $dot .= "                <tr><td colspan=\"2\" align=\"left\">{$addrHost}/<font color=\"red\">{$addrMask}</font></td></tr>\n";
    $dot .= "                <tr><td colspan=\"2\" align=\"left\">{$gateway}</td></tr>\n";
    $dot .= "              </table>\n";
    $dot .= "            >\n";
    $dot .= "        ];\n";
    $dot .= "    }\n";
}

$dot .= "}\n";
@endphp

<script>
document.addEventListener('graphvizReady', () => {
    document.getElementById("graph").innerHTML = window.graphviz.layout(
        @json($dot),
        "svg",
        "osage"
    );
});
</script>
@parent
@endsection