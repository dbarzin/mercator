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
                    <table width="100%" border=0>
                        <tr class="explore_commands">
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
                                            <option value="7">{{ trans("cruds.report.cartography.network_infrastructure") }}</option>
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
                            <td style="text-align: center; vertical-align: middle; width: 2rem;">
                                    <a href="#" id="add_node_button" onclick="addNode()">
                                        <i class="fas fa-plus"></i>
                                    </a>
                            </td>
                            <td width=10>
                            </td>
                            <td style="text-align: left; vertical-align: middle;">
                                                            </td>
                            <td style="text-align: right; vertical-align: middle;">
                                &nbsp;
                                <a onclick="needSavePNG=true; network.redraw();document.getElementById('canvasImg').click();" href="#"><i class="fas fa-camera-retro"></i>
                                Photo
                                </a>
                                <a id="canvasImg" download="filename"></a>
                            </td>
                        </tr>
                    </table>   
                      <div class="explore_commands">
                        <a href="#" onclick="network.deleteSelected()" class="command">
                          <i class="fas fa-minus-circle"></i>
                                    {{ trans("cruds.report.explorer.delete") }}                                    
                        </a>
                                &nbsp;
                                <a href="#" onclick="nodes.clear(); edges.clear(); network.redraw();" class="command">
                                    <i class="fas fa-repeat">

                                    </i>
                                    {{ trans("cruds.report.explorer.reload") }}
                                </a>
                                <input type="checkbox" id="physicsCheckbox" class="command" checked> <label for="physicsCheckbox">{{trans("cruds.report.explorer.physics")}}</label>
                        <div class="command">
                              <select id="depth">
                                <option value="1">1</option>
                                <option value="2" >2</option>
                                <option value="3" selected>3</option>
                                <option value="4" >4</option>
                                <option value="5" >5</option>
                              </select>
                            <a href="#" onclick="deployAll()">
                          <i class="fas fa-star"></i>
                                    {{ trans("cruds.report.explorer.deploy") }}                                    
                        </a>

                      </div>

                  </div>                 
                </div>
                <div id="mynetwork" style="height:700px;"></div>
              </div>
        </div>
    </div>
</div>
<ul id="explore_context"></ul>
@endsection


@section('scripts')

<script src="/js/vis-network.min.js"></script>

<script>
    let nodes = null;
    let edges = null;
    let network = null;

    let _nodes = new Map();
    @foreach($nodes as $node) 
        _nodes.set( "{{ $node["id"] }}" ,{ id: "{{ $node["id"]}}", vue: "{{ $node["vue"]}}", label: "{!! str_replace('"','\\"',$node["label"]) !!}", {!! array_key_exists('title',$node) ? ('title: "' . $node["title"] . '",') : "" !!} image: "{{ $node["image"] }}",  type: "{{ $node["type"] }}", edges: [ <?php 
        foreach($edges as $edge) {
            if ($edge["from"]==$node["id"])
                echo '{id:"' . $edge["to"] . ($edge["name"]!==null ? '",name:"' . $edge["name"] : ""). '",edgeType:"' . $edge["type"] .'", edgeDirection: "TO", bidirectional:'. ($edge["bidirectional"]?"true":"false") . '},';
            if ($edge["to"]==$node["id"])
                echo '{id:"' . $edge["from"] . ($edge["name"]!==null ? '",name:"' . $edge["name"] : ""). '",edgeType:"' . $edge["type"] .'", edgeDirection: "FROM", bidirectional:' . ($edge["bidirectional"]?"true":"false") . '},';
            } ?> ]}); 
    @endforeach

    // Add a node base on the node.id
    function addNode() {
        let id=document.getElementById('node').value
        let new_node = _nodes.get(id);
        // add node
        console.log("add node: "+new_node.id);
        network.body.data.nodes.add(new_node);
        // add edges
       if ((nodes.get(target_node.id) != null) && (exists(new_node.id, target_node.id).length == 0)) {
    console.log("add edge :" + new_node.id + " -> " + target_node.id);
    addEdge(new_node.id, target_node.id);
}        // redraw
        network.redraw();
    };

    // Called when the Visualization API is loaded.
    function draw() {
        // Start node 
        @if (Request::get("node") !== null)
            nodes = new vis.DataSet([_nodes.get("{{ Request::get("node") }}")]);
        @else
            nodes = new vis.DataSet();

        @endif

        // create an array with edges
        edges = new vis.DataSet([]);

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
            shape:'circularImage',
            size: 30,
            color: { border: "#aaaaaa", background: "#ffffff"},
            imagePadding: 10,
            font: { color: "#000000", background: "#ffffff"},

          },
          edges: {
            color: "#333333"
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
        console.log(_nodes);

        network.on("doubleClick", function (params) {
            console.log("doubleClick on : "+params.nodes[0]);
            let new_node = _nodes.get(params.nodes[0]);
            if (new_node === undefined)
                return;
            let edgeList = new_node.edges;
            let filter = getFilter();
            
            // Loop on all links
            for (const edge of edgeList) {
                // Get destination node
                let new_node = _nodes.get(edge.id);
                if (new_node!=null) {
                    // Apply filter
                    if (
                        (filter.length==0) || filter.includes(new_node.vue) || 
                        (filter.includes("7") && edge.edgeType === 'CABLE')
                    ) { 
                        // Check node already present
                        if (nodes.get(edge.id)==null) {
                            nodes.add(new_node);
                        }
                        // Check link already present
                        if (exists(params.nodes[0], edge.id).length==0) 
                        {
                            if(edge.edgeType === 'FLUX') {
                                if(edge.edgeDirection === 'TO') {
                                    if (edge.bidirectional)
                                        edges.add({ label: edge.name, from: edge.id, to: params.nodes[0], length:200, arrows: {to: {enabled: true, type: 'arrow'}, from: {enabled: true, type: 'arrow'}} });
                                    else
                                        edges.add({ label: edge.name, from: params.nodes[0], to: edge.id, length:200, arrows: {to: {enabled: true, type: 'arrow'}} });
                                } else if(edge.edgeDirection === 'FROM') {
                                    if (edge.bidirectional)
                                        edges.add({ label: edge.name, from: edge.id, to: params.nodes[0], length:200, arrows: {to: {enabled: true, type: 'arrow'}, from: {enabled: true, type: 'arrow'}} })
                                    else
                                        edges.add({ label: edge.name, from: params.nodes[0], to: edge.id, length:200, arrows: {from: {enabled: true, type: 'arrow'}} })
                                }
                            } else if(edge.edgeType === 'CABLE') {
                                edges.add({ from: params.nodes[0], to: edge.id, color:'grey', width: 3 });                          
                            } else if(edge.edgeType === 'LINK') {
                                edges.add({ from: params.nodes[0], to: edge.id});
                            }
                        }
                    }
                }
            network.redraw();
            }
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
            console.log(nodeId);
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
        let dataURL = ctx.canvas.toDataURL();
        document.getElementById('canvasImg').href = dataURL;
      });

    }

    const network_container = document.getElementById('mynetwork');

    document.addEventListener('keypress', fullscreen_network);

    function fullscreen_network(e) {
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
          physics: {
            enabled: true
          },
          edges: {
            smooth: true
          }  
        });
      } else {
        network.setOptions({
          physics: {
            enabled: false
          },
          edges: {
            smooth: false
          }  
        });
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
      if (depth <= 0 || visitedNodes.has(nodeId)) {
        return;
      }

      visitedNodes.add(nodeId);

      let node = _nodes.get(nodeId);
      if (!node) {
        return;
      }

      let edgeList = node.edges;
      for (const edge of edgeList) {
        let targetNodeId = edge.id;

        if (nodes.get(targetNodeId) === null) {
          let targetNode = _nodes.get(targetNodeId);
          nodes.add(targetNode);
        }

        if (exists(nodeId, targetNodeId).length === 0) {
          addEdge(nodeId, targetNodeId);
        }
       setTimeout(function() {
            deployFromNode(targetNodeId, depth - 1, visitedNodes);
       }, 500);
      }
    }


function addEdge(sourceNodeId, targetNodeId) {
    var edgeList = _nodes.get(sourceNodeId).edges;
    for (const edge of edgeList) {
        if (edge.id === targetNodeId) {
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
                edges.add({ from: sourceNodeId, to: targetNodeId, color: 'grey', width: 3 });
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


/* TODO : Fixme

    function apply_filter() {
        console.log("apply_filter");
        // clear current selected node
        // $("#node").val("");
        // reinitialize component
        $("#node").select2("destroy").select2();

        // get current filter
        cur_filter = $('#filters').val();
        // test filter size
        console.log("filter_size= ",cur_filter.length);
        if (cur_filter.length==0) {
            console.log("activate all nodes");
            $('#node').find("option").each(function( index) {
                $(this).attr('disabled',false);
            });
        }
        else 
        {
            // filter nodes
            let activated=0, disabled=0;
            $('#node').find("option").each(function( index) {
                let cur_node = _nodes.get(this.value);
                if (cur_node!=null) {
                    if (cur_filter.includes(cur_node.vue)) {
                    // $(this).attr('disabled', false).trigger("select2.change");
                    $(this).attr('disabled',true); <--- here
                    activated++;
                    }
                    else {
                        // $(this).attr('disabled', true).trigger("select2.change");
                        $(this).attr('disabled',false);  <--- here
                        disabled++;
                    }
                }
            });
            console.log("disable= ",disabled," activated= ",activated);
          }
        $('#node').trigger("change");
    }

    $('.select2').select2();

    $('#filters')
        .on('select2:select', function(e) {
            console.log('Select: ' , e.params.data.id);
            console.log('val: ' ,   $('#filters').val());
            apply_filter();
            //$('#node').select2();
        });

    $('#filters')
        .on('select2:unselect', function(e) {
            console.log('unSelect: ' , e.params.data.id);
            console.log('val: ' ,   $('#filters').val());
            apply_filter();
            //$('#filter').select2();
        });
    
*/

</script>

@parent

@endsection
