@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    {{ trans("cruds.report.explorer.title") }}
                </div>
                <div class="card-body">
                    <table border=0>
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
                                            <option value="6">{{ trans("cruds.report.cartography.physical_infrastructure") }}</option>
                                    </select>
                                    <span class="help-block">{{ trans("cruds.report.explorer.filter_helper") }}</span>
                                </div>
                            </td>
                            <td width=10>
                            </td>
                            <td width="400">
                                <div class="form-group">
                                    <label for="title">{{ trans("cruds.report.explorer.object") }}</label>
                                    <select class="form-control select2" id="node" >
                                        <option></option>
                                        @foreach($nodes as $node) 
                                        <option value="{{ $node['id'] }}">{{ $node["label"] }}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">{{ trans("cruds.report.explorer.object_helper") }}</span>
                                </div>
                            </td>
                            <td style="text-align: center; vertical-align: middle">
                                    <a href="#" onclick="addNode()">
                                        <i class="fas fa-plus"></i>
                                    </a>
                            </td>
                            <td width=20>
                            </td>
                            <td style="text-align: center; vertical-align: middle;">
                                <!-- 
                                <a href="#">
                                    <i class="fas fa-plus-circle">                                        
                                    </i>
                                    Open
                                </a>
                                -->
                                <a href="#" onclick="network.deleteSelected()">
                                    <i class="fas fa-minus-circle">
                                        
                                    </i>
                                    {{ trans("cruds.report.explorer.delete") }}                                    
                                </a>
                                <a href="#" onclick="location.reload()">
                                    <i class="fas fa-repeat">
                                        
                                    </i>
                                    {{ trans("cruds.report.explorer.reload") }}
                                </a>
                                <!-- TODO -->
                                <!-- <a href="#" id="download" download="mercator.png"><button type="button" onClick="download()">Download</button></a>-->
                            </td>
                        </tr>
                    </table>
                    <!--
                    <a href="#"><i class="fas fa-camera-retro"></i></a>
                    -->
                </div>
                <div id="mynetwork" style="height:700px;"></div>
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
        _nodes.set( "{{ $node["id"] }}" ,{ id: "{{ $node["id"]}}", vue: "{{ $node["vue"]}}", label: "{!! str_replace('"','\\"',$node["label"]) !!}", {!! array_key_exists('title',$node) ? ('title: "' . $node["title"] . '",') : "" !!} image: "{{ $node["image"] }}", shape: IMG });
        _edges.set( "{{ $node["id"] }}", [ <?php 
        foreach($edges as $edge) {
            if ($edge["from"]==$node["id"])
                echo '{id:"' . $edge["to"] .'",edgeType:"' . $edge["type"] .'", edgeDirection: "TO"},';
            if ($edge["to"]==$node["id"])
                echo '{id:"' . $edge["from"] .'",edgeType:"' . $edge["type"] .'", edgeDirection: "FROM"},';
            } ?> ]); 
    @endforeach

    // Add a node base on the node.id
    function addNode() {
        var id=document.getElementById('node').value
        var new_node = _nodes.get(id);
        // add node
        console.log("add node :"+new_node.id);
        network.body.data.nodes.add(new_node);
        // add edges
        var nodeList = _edges.get(new_node.id);
        if (nodeList === undefined)
            return;

        // Loop on all links
        for (const node of nodeList) {
            // Get destination node
            var target_node = _nodes.get(node.id);
            // check node exists
            if (target_node!=null) {
                // Check node already present
                if ((nodes.get(target_node.id)!=null)&&(exists(new_node.id, target_node.id).length==0)) {
                    console.log("add edge :"+new_node.id+" -> " +target_node.id);
                    if(node.edgeType === 'FLUX') {
                        if(node.edgeDirection === 'TO') {
                            edges.add({ from: new_node.id, to: target_node.id, arrows: {to: {enabled: true, type: 'arrow'}} });
                        } else if(node.edgeDirection === 'FROM') {
                            edges.add({ from: new_node.id, to: target_node.id, arrows: {from: {enabled: true, type: 'arrow'}} })
                        }
                    } else if(node.edgeType === 'LINK') {
                        edges.add({ from: new_node.id, to: target_node.id });
                    }
                }
            }
        }
        // redraw
        network.redraw();
    };

    // Called when the Visualization API is loaded.
    function draw() {
        // Start node 
        @if (Request::get("node")!=null)
            nodes = new vis.DataSet([_nodes.get("{{ Request::get("node") }}")]);
        @else
            nodes = new vis.DataSet();
        @endif

        // create an array with edges
        edges = new vis.DataSet([]);

        // create a network
        var container = document.getElementById("mynetwork");
        var data = {
            nodes: nodes,
            edges: edges,
        };

        var options = {
          interaction:{
            dragNodes:true,
            dragView: true,
            hideEdgesOnDrag: false,
            hideEdgesOnZoom: false,
            hideNodesOnDrag: false,
            hover: true,
            hoverConnectedEdges: true,
            keyboard: {
              enabled: false,
              speed: {x: 10, y: 10, zoom: 0.02},
              bindToWindow: true,
              autoFocus: true,
            },
            multiselect: true,
            navigationButtons: true,
            selectable: true,
            selectConnectedEdges: true,
            tooltipDelay: 300,
            zoomSpeed: 1,
            zoomView: true,
            navigationButtons: true,
          }
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

        network.on("click", function (params) {
            console.log("click on : "+params.nodes[0]);
            //nodes.remove(params.nodes[0]);
        });

        network.on("doubleClick", function (params) {
            console.log("doubleClick on : "+params.nodes[0]);
            var nodeList = _edges.get(params.nodes[0]);
            if (nodeList === undefined)
                return;

            // get filter
            var filter = [];
            for (var option of document.getElementById('filters').options)
                if (option.selected) 
                    filter.push(option.value);
            console.log("filter :"+filter);

            // Loop on all links
            for (const node of nodeList) {
                // Get destination node
                var new_node = _nodes.get(node.id);
                if (new_node!=null) {
                    console.log(new_node.vue)
                    // Apply filter
                    if ((filter.length==0) || filter.includes(new_node.vue)) { 
                        // Check node already present
                        if (nodes.get(node.id)==null) {
                            console.log("add node :"+node.id);
                            nodes.add(new_node);
                        }
                        // Check link already present
                        if (exists(params.nodes[0], node.id).length==0) 
                        {
                            console.log("add edge :"+params.nodes[0]+" -> " +node.id);
                            if(node.edgeType === 'FLUX') {
    							if(node.edgeDirection === 'TO') {
    								edges.add({ from: params.nodes[0], to: node.id, arrows: {to: {enabled: true, type: 'arrow'}} });
    							} else if(node.edgeDirection === 'FROM') {
    								edges.add({ from: params.nodes[0], to: node.id, arrows: {from: {enabled: true, type: 'arrow'}} })
    							}
    						} else if(node.edgeType === 'LINK') {
    							edges.add({ from: params.nodes[0], to: node.id });
    						}
                        }
                    }
                }
            network.redraw();
            }
        });
    }

</script>

@parent

@endsection
