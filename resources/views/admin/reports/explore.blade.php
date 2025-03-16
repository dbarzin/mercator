@extends('layouts.admin')

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header">
                {{ trans("cruds.report.explorer.title") }}
            </div>
            <div class="card-body">
                <table width="100%" border=0>
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
                                    @foreach($nodes as $node)
                                    <option value="{{ $node['id'] }}">{{ $node["label"] }}</option>
                                    @endforeach
                                </select>
                                <span class="help-block">{{ trans("cruds.report.explorer.object_helper") }}</span>
                            </div>
                        </td>
                        <td style="text-align: left; vertical-align: middle;">
                            <a href="#" id="add_node_button" onclick="addNode(document.getElementById('node').value)">
                                <i class="bi bi-plus-square-fill"></i>
                            </a>
                        </td>
                        <td style="text-align: right; vertical-align: right;">
                            &nbsp;
                            <a onclick="needSavePNG=true; network.redraw();document.getElementById('canvasImg').click();" href="#"><i class="fas fa-camera-retro"></i>
                            Photo
                            </a>
                            <a id="canvasImg" download="filename"></a>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3">
                    <a href="#" onclick="network.deleteSelected()" class="command">
                        <i class="fas fa-minus-circle"></i>
                        {{ trans("cruds.report.explorer.delete") }}
                    </a>
                    &nbsp;
                    <a href="#" onclick="nodes.clear(); edges.clear(); network.redraw();" class="command">
                        <i class="fas fa-repeat"></i>
                        {{ trans("cruds.report.explorer.reload") }}
                    </a>
                    <input type="checkbox" id="physicsCheckbox" class="command" checked>
                    <label for="physicsCheckbox">{{trans("cruds.report.explorer.physics")}}</label>
                    &nbsp;
                    &nbsp;
                    <select id="depth">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3" selected>3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                    </select>
                        <a href="#" onclick="deployAll()">
                            <i class="fas fa-star"></i>
                            {{ trans("cruds.report.explorer.deploy") }}
                        </a>
                     </td>
                </tr>
            </table>
            </div>
            <div id="mynetwork" style="height:500px;"></div>
          </div>
    </div>
</div>
<ul id="explore_context"></ul>
@endsection


@section('scripts')

@vite(['resources/js/vis-network.js'])
<script>

    let nodes = null;
    let edges = null;
    let network = null;
    let needSavePNG = false;

    // TODO : optimize me
    let _nodes = new Map();
    @foreach($nodes as $node)
        _nodes.set( "{{ $node["id"] }}" ,{ id: "{{ $node["id"]}}", vue: "{{ $node["vue"]}}", label: "{!! str_replace('"','\\"',$node["label"]) !!}", {!! array_key_exists('title',$node) ? ('title: "' . $node["title"] . '",') : "" !!} image: "{{ $node["image"] }}",  type: "{{ $node["type"] }}", edges: [ <?php
        foreach($edges as $edge) {
            if ($edge["from"]==$node["id"])
                echo '{attachedNodeId:"' . $edge["to"] . ($edge["name"]!==null ? '",name:"' . $edge["name"] : ""). '",edgeType:"' . $edge["type"] .'", edgeDirection: "TO", bidirectional:'. ($edge["bidirectional"]?"true":"false") . '},';
            if ($edge["to"]==$node["id"])
                echo '{attachedNodeId:"' . $edge["from"] . ($edge["name"]!==null ? '",name:"' . $edge["name"] : ""). '",edgeType:"' . $edge["type"] .'", edgeDirection: "FROM", bidirectional:' . ($edge["bidirectional"]?"true":"false") . '},';
            } ?> ]});
    @endforeach

    // Add a node base on the node.id
    function addNode(id) {
        var new_node = _nodes.get(id);
        if (new_node==null)
            return;
        // Check node already present
        if (!nodes.get(new_node.id)) {
            // Add node
            // console.log("add node: ",new_node.id);
            network.body.data.nodes.add(new_node);
            }
        // add edges
        var edgeList = new_node.edges;
        if (edgeList === undefined)
            return;

        // Loop on all edges
        for (const edge of edgeList) {
            // Get destination node
            var target_node = _nodes.get(edge.attachedNodeId);
            // check node exists
            if (target_node !== undefined) {
                    if ((nodes.get(target_node.id)!=null)&&(exists(new_node.id, target_node.id, edge.name).length==0)) {
                        // console.log("add edge: ", new_node.id, " -> ", target_node.id);
                        if (edge.edgeType === 'FLUX') {
                            // console.log('edge.label=', edge.name)
                            if (edge.edgeDirection === 'TO') {
                                if (edge.bidirectional)
                                    edges.add({ label: edge.name, from: target_node.id, to: new_node.id, length:200, arrows: {from: {enabled: true, type: 'arrow'}, to: {enabled: true, type: 'arrow'}} });
                                else
                                    edges.add({ label: edge.name, from: new_node.id, to: target_node.id, length:200, arrows: {to: {enabled: true, type: 'arrow'}} });
                            } else if (edge.edgeDirection === 'FROM') {
                                if (edge.bidirectional)
                                    edges.add({ label: edge.name, from: target_node.id, to: new_node.id, length:200, arrows: {from: {enabled: true, type: 'arrow'},to: {enabled: true, type: 'arrow'}} })
                                else
                                    edges.add({ label: edge.name, from: new_node.id, to: target_node.id, length:200, arrows: {from: {enabled: true, type: 'arrow'}} })
                            }
                        } else if(edge.edgeType === 'CABLE') {
                            edges.add({ from: params.nodes[0], to: edge.attachedNodeId, color:'blue', width: 5 });
                        } else if (edge.edgeType === 'LINK') {
                            // do not add links if "network infrastructure" is selected
                            if (!$('#filters').val().includes("7"))
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

        nodes = new DataSet();

        // create an array with edges
        edges = new DataSet([]);

        // create a network
        let container = document.getElementById("mynetwork");
        let data = {
            nodes: nodes,
            edges: edges,
        };

        let options = {
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
          },
          nodes: {
            shape:'image',
            size: 30,
            color: { border: "#fffffff", background: "#ffffff"},
            imagePadding: 10,
            font: { color: "#000000", background: "#ffffff"},

          },
          edges: {
            color: "#333333"
          }
        };

        network = new Network(container, data, options);

        // Add Nodes from parameter
        @if (Request::get("node")!==null)
            @foreach(explode(',',Request::get("node")) as $node)
                addNode("{{$node}}");
            @endforeach
        @endif
    }

    // check if edge between node1 and node2 already exists
    function exists(node1, node2, name) {
        //console.log("Check exists link ",name)
        return edges.get().filter(function (edge) {
           return (edge.name == name)&&(edge.from === node1 && edge.to === node2) || (edge.from === node2 && edge.to === node1);
        });
    }

    window.onload = function() {
        draw();

        network.on("click", function (params) {
            // console.log("click on : "+params.nodes[0]);
            //nodes.remove(params.nodes[0]);
        });
        // console.log(_nodes);

        network.on("doubleClick", function (params) {

            // Store IDs of newly added nodes in order to impact options later (physics)
            let newlyAddedNodes = [];

            let new_node = _nodes.get(params.nodes[0]);
            if (new_node === undefined) return;
            let edgeList = new_node.edges;
            let filter = getFilter();

    // Loop on all links
    for (const edge of edgeList) {
        // Get destination node
        let new_node = _nodes.get(edge.attachedNodeId);
        if (new_node != null) {
            if (
                ((filter.length == 0) || filter.includes(new_node.vue))
                ||
                (filter.includes("8") && (edge.edgeType === 'CABLE'))
                ||
                (filter.includes("9") && (edge.edgeType === 'FLUX'))
            ) {
                // Check node already present
                if (!nodes.get(new_node.id))
                {
                    nodes.add({ ...new_node, physics: true });
                    newlyAddedNodes.push(new_node.id);
                }

                if (exists(params.nodes[0], edge.attachedNodeId, edge.name).length==0)
                {
                    var existingEdge = edges.get({
                        filter: function (e) {
                          return (
                            (
                                (e.from === params.nodes[0] && e.to === edge.attachedNodeId) ||
                                (e.from === edge.attachedNodeId && e.to === params.nodes[0])) &&
                            e.label === edge.name
                          );
                        }
                    }).length>0;

                    // console.log("existingEdge="+existingEdge);
                    if (existingEdge)
                        continue;

                    // console.log("add edge "+edge.name);

                    if (edge.edgeType === 'FLUX') {
                        if(edge.edgeDirection === 'TO') {
                            if (edge.bidirectional)
                                edges.add({ label: edge.name, from: edge.attachedNodeId, to: params.nodes[0], length:200, arrows: {to: {enabled: true, type: 'arrow'}, from: {enabled: true, type: 'arrow'}}, physics: true });
                            else
                                edges.add({ label: edge.name, from: params.nodes[0], to: edge.attachedNodeId, length:200, arrows: {to: {enabled: true, type: 'arrow'}}, physics: true });
                        } else if(edge.edgeDirection === 'FROM') {
                            if (edge.bidirectional)
                                edges.add({ label: edge.name, from: edge.attachedNodeId, to: params.nodes[0], length:200, arrows: {to: {enabled: true, type: 'arrow'}, from: {enabled: true, type: 'arrow'}}, physics: true })
                            else
                                edges.add({ label: edge.name, from: params.nodes[0], to: edge.attachedNodeId, length:200, arrows: {from: {enabled: true, type: 'arrow'}}, physics: true })
                        }
                    } else if (edge.edgeType === 'CABLE') {
                        edges.add({ from: params.nodes[0], to: edge.attachedNodeId, color:'blue', width: 5, physics: true });
                    } else if (edge.edgeType === 'LINK') {
                        edges.add({ from: params.nodes[0], to: edge.attachedNodeId, physics: true });
                    }
                }
            }
        }
    }

    // If global physics is disabled, disable physics of newly deployed nodes after some time
    if (!physicsCheckbox.checked) {
        setTimeout(() => {
            nodes.update(newlyAddedNodes.map(id => ({ id, physics: false })));
            }, 1500);
        }

    network.redraw();
    });
        // Adds a contextmenu for quickaccess from nodes

        let contextMenu = document.getElementById("explore_context");
        let x = null;
        let y = null;

        document.addEventListener('mousemove', onMouseUpdate, false);
        document.addEventListener('mouseenter', onMouseUpdate, false);

        function onMouseUpdate(e) {
            x = e.pageX;
            y = e.pageY;
        }

        function displayContext(){
            contextMenu.style.display="block";
            contextMenu.style.opacity = "1";
            contextMenu.style.top=y + "px";
            contextMenu.style.left=x + "px";
        }

        function hideContext(){
            contextMenu.style.opacity = "0";
            contextMenu.style.display = "none";
        }

        network.on("click", () => hideContext());

        network.on("oncontext", function(e){
          e.event.preventDefault();
          let s = network.getNodeAt({
          x: e.pointer.DOM.x,
          y: e.pointer.DOM.y
        });

        if (s) {
            link = s;
            let nodeId = link.split("_").pop();
            // console.log(nodeId);
            let node = _nodes.get(link);
            let type = node.type;
            contextMenu.innerHTML = "<li><a href='/admin/"+type+"/"+nodeId+"'>{{ trans("global.view") }}</a></li>" +
                                    "<li><a href='/admin/"+type+"/"+nodeId+"/edit'>{{ trans("global.edit") }}</a></li>" +
                                    "<li id='hideNode' style='color: #167495; cursor: pointer;' ><span>{{ trans("global.hide") }}</span></li>"
            displayContext();

            let hideNode = document.getElementById("hideNode");
            hideNode.addEventListener("click", function(){
                let node = _nodes.get(link);
                network.body.data.nodes.remove(node);
                hideContext();
            });

          } else {
              hideContext();
          }
      });

      // Draw image
      network.on("afterDrawing", function (ctx) {
        if (needSavePNG) {
            var dataURL = ctx.canvas.toDataURL();
            document.getElementById('canvasImg').href = dataURL;
            // console.log("convert PNG");
            needSavePNG = false;
            }
      });

    }

    const network_container = document.getElementById('mynetwork');

    document.addEventListener('keypress', handle_keypressed);

    function handle_keypressed(e) {
      // console.log("e.key= "+e.key)
      if (e.key === "F"){

        if (document.activeElement.classList.contains("select2-search__field"))
          return;

        if (network_container.classList.contains('fullscreen_network')){
          network_container.classList.remove('fullscreen_network');
          return;
        }
        network_container.classList.add('fullscreen_network');
        return;
      }
      if (e.key === "Escape"){
        network_container.classList.remove('fullscreen_network');
      }
    }


    // Enable/Disable physics on network
    let physicsCheckbox = document.getElementById('physicsCheckbox');

    physicsCheckbox.addEventListener('change', function() {
      if (this.checked) {
        network.setOptions({
        nodes: {
            physics: true
            },
            edges: {
                smooth: {
                type: "dynamic"
                }
            }
        });
        // update all nodes
        nodes.update(nodes.getIds().map(id => ({ id, physics: true })));
        } else {
        network.setOptions({
           nodes: {
             physics: false
            },
        edges: {
            smooth: {
              type: "continuous"
            }
          }
        });
        // update all nodes
        nodes.update(nodes.getIds().map(id => ({ id, physics: false  })));
        }
    });

    // Toggle physics by keypress "p"
    document.addEventListener('keypress', togglePhysicsOnP);

    function togglePhysicsOnP(event) {
    if (event.key === "p" || event.key === "P") {
        let physicsCheckbox = document.getElementById('physicsCheckbox');
        physicsCheckbox.checked = !physicsCheckbox.checked; // bascule l'état de la checkbox

        physicsCheckbox.dispatchEvent(new Event('change'));
    }
}

    // Deploys all edges and nodes from a selected node, until it reaches a #depth value
    function deployAll() {
      let activeNode = network.getSelectedNodes()[0];
      if (!activeNode) {
        alert("{{ trans("cruds.report.explorer.please_select") }}");
        return;
      }

      let depth = parseInt(document.getElementById('depth').value);
      let visitedNodes = new Set();
      let filter = getFilter();

      deployFromNode(activeNode, depth, visitedNodes, filter);
    }

    function deployFromNode(nodeId, depth, visitedNodes, filter) {
      // Limit recursion
      if (depth <= 0 || visitedNodes.has(nodeId)) {
        return;
      }
      visitedNodes.add(nodeId);

      // Deploy
      let node = _nodes.get(nodeId);
      if (!node) {
        return;
      }

      // Loop on edges
      let edgeList = node.edges;
      for (const edge of edgeList) {
        let targetNodeId = edge.attachedNodeId;

        if (nodes.get(targetNodeId) === null) {
            let targetNode = _nodes.get(targetNodeId);
            if (targetNode == null)
                continue;

            // Filter target node type
            if (
                ((filter.length == 0) || filter.includes(targetNode.vue))
                ||
                (filter.includes("8") && (edge.edgeType === 'CABLE'))
                ||
                (filter.includes("9") && (edge.edgeType === 'FLUX'))
            ) {
                // Check node already present
                if (!nodes.get(targetNodeId)) {
                    nodes.add(targetNode);

                if (exists(nodeId, targetNodeId, edge.name).length === 0) {
                  addEdge(nodeId, targetNodeId);
                }
                setTimeout(function() {
                    deployFromNode(targetNodeId, depth - 1, visitedNodes, filter);
                    }, 500);
                }
            }
        }
    }
}

    function addEdge(sourceNodeId, targetNodeId) {
        var edgeList = _nodes.get(sourceNodeId).edges;
        for (const edge of edgeList) {
            if (edge.attachedNodeId === targetNodeId) {
                if (edge.edgeType === 'FLUX') {
                    if (edge.edgeDirection === 'TO') {
                        if (edge.bidirectional)
                            edges.add({ label: edge.name, from: targetNodeId, to: sourceNodeId, length: 200, arrows: { to: { enabled: true, type: 'arrow' }, from: { enabled: true, type: 'arrow' } } });
                        else
                            edges.add({ label: edge.name, from: sourceNodeId, to: targetNodeId, length: 200, arrows: { to: { enabled: true, type: 'arrow' } } });
                    } else if (edge.edgeDirection === 'FROM') {
                        if (edge.bidirectional)
                            edges.add({ label: edge.name, from: targetNodeId, to: sourceNodeId, length: 200, arrows: { to: { enabled: true, type: 'arrow' }, from: { enabled: true, type: 'arrow' } } });
                        else
                            edges.add({ label: edge.name, from: sourceNodeId, to: targetNodeId, length: 200, arrows: { from: { enabled: true, type: 'arrow' } } });
                    }
                } else if (edge.edgeType === 'CABLE') {
                    edges.add({ from: sourceNodeId, to: targetNodeId, color: 'blue', width: 5 });
                } else if (edge.edgeType === 'LINK') {
                    edges.add({ from: sourceNodeId, to: targetNodeId });
                }
            }
        }
    }

    // Gets filtered entities from #filter field
    function getFilter(){
        let filter = [];
        for (let option of document.getElementById('filters').options)
            if (option.selected)
              filter.push(option.value);
        return filter
    }

    function apply_filter() {
        // Get current filter
        cur_filter = $('#filters').val();

        // Get filter size
        if (cur_filter.length==0) {
            for (let [node, value] of _nodes)
                $("#node").append('<option value="' + value.id + '">' + value.label + '</option>');
        }
        else
        {
            // filter nodes
            let activated=0, disabled=0;
            $("#node").empty();
            for (let [node, value] of _nodes) {
                if (cur_filter.includes(value.vue)) {
                    $("#node").append('<option value="' + value.id + '">' + value.label + '</option>');
                    activated++;
                    }
                else
                    disabled++;
            }
        }
        $('#node').val(null).trigger("change");
    }
document.addEventListener('DOMContentLoaded', () => {
    $('body').keydown(function(event){
        // delete
        if((event.keyCode == 8)||(event.keyCode == 46)) {
            network.deleteSelected()
        }
     });

    // clear selections
    $('#filters').val(null).trigger('change');
    $('#node').val(null);

    $('#filters')
        .on('select2:select', function(e) {
            apply_filter();
        });

    $('#filters')
        .on('select2:unselect', function(e) {
            apply_filter();
        });
    });

</script>

@parent

@endsection
