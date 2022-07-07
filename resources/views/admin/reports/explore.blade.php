@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    Exploration de la cartographie
                </div>
                <div id="mynetwork" style="height:800px;"></div>
              </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')

<script src="/js/vis-network.min.js"></script>

<script>

      const IMG = "image";
      
      var nodes = null;
      var edges = null;
      var network = null;


      var _nodes = new Map();
      var _edges = new Map();
      @foreach($nodes as $node) 
        _nodes.set( "{{ $node["id"] }}" ,{ id: "{{ $node["id"]}}", label: "{!! str_replace('"','\\"',$node["label"]) !!}", {!! array_key_exists('title',$node) ? ('title: "' . $node["title"] . '",') : "" !!} image: "{{ $node["image"] }}", shape: IMG });
        _edges.set( "{{ $node["id"] }}", [ <?php 
        foreach($edges as $edge) {
            if ($edge["from"]==$node["id"])
                echo '"' . $edge["to"] .'",';
            if ($edge["to"]==$node["id"])
                echo '"' . $edge["from"] .'",';
            } ?> ]); 
      @endforeach

      // Called when the Visualization API is loaded.
      function draw() {
        // Start node 
        nodes = new vis.DataSet([_nodes.get("{{ Request::get("node") }}")]);

        // create an array with edges
        edges = new vis.DataSet([]);

        // create a network
        var container = document.getElementById("mynetwork");
        var data = {
          nodes: nodes,
          edges: edges,
        };
        var options = {
            interaction: { hover: true },
            manipulation: {
              enabled: false,
            },
        };
        network = new vis.Network(container, data, options);
      }

    // check if edge between node1 and node2 already exists
    function exists(node1, node2) {
        return edges.get().filter(function (edge) {
           return (edge.from === node1 && edge.to === node2) || (edge.from === node2 && edge.to === node1);
        });
    }

    window.onload = function() {
        draw();
          network.on("doubleClick", function (params) {
            console.log("doubleClick on : "+params.nodes[0]);
            var nodeList = _edges.get(params.nodes[0]);
            if (nodeList !== undefined)
            for (const node of nodeList) {
                var new_node = _nodes.get(node);
                if (new_node!=null) {
                    if (nodes.get(node)==null) {
                        console.log("add node :"+node);
                        nodes.add(new_node);
                    }
                    if (exists(params.nodes[0], node).length==0) 
                    {
                        console.log("add edge :"+params.nodes[0]+" -> " +node);
                        edges.add({ from: params.nodes[0], to: node })
                        }
                }
            network.redraw();
            }
          });
        }

</script>

@parent

@endsection
