@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card explorer-screen">
                <div class="card-header">
                    {{ trans("cruds.report.explorer.title") }}
                </div>
                <div class="card-body">
                    <!-- Loading indicator -->
                    <div id="loading-indicator" class="alert alert-info">
                        <i class="fas fa-spinner fa-spin"></i>
                        <span id="loading-text">Chargement des données...</span>
                    </div>

                    <!-- Controls (hidden until data loaded) -->
                    <div id="explorer-controls" style="display: none;">
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
                                <td style="text-align: left; vertical-align: middle;">
<button type="button" class="btn btn-primary" onclick="addNode(document.getElementById('node').value)">
<i class="bi bi-plus-square-fill"></i>&nbsp;Ajouter
</button>
                                </td>
                                <td style="text-align: right; vertical-align: top;">
                                    &nbsp;
                                    <a onclick="needSavePNG=true; network.redraw();document.getElementById('canvasImg').click();"
                                       href="#"><i class="fas fa-camera-retro"></i>
                                        Photo
                                    </a>
                                    <a id="canvasImg" download="filename"></a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="4">
<button type="button" class="btn btn-danger" onclick="network.deleteSelected()">
<i class="bi bi-dash-circle"></i>&nbsp;{{ trans("cruds.report.explorer.delete") }}
</button>
&nbsp;
<button type="button" class="btn btn-warning" onclick="network.unselectAll(); nodes.clear(); edges.clear(); network.redraw();">
<i class="bi bi-arrow-repeat"></i>&nbsp;{{ trans("cruds.report.explorer.reload") }}
</button>
&nbsp;&nbsp;
Physique :
<button id="physicsToggle" class="btn btn-success physics-active" type="button">
    <i class="bi bi-play-fill"></i>
</button>
                                    &nbsp;
                                    &nbsp;
                                    <select id="depth">
                                        <option value="1">1</option>
                                        <option value="2">2</option>
                                        <option value="3" selected>3</option>
                                        <option value="4">4</option>
                                        <option value="5">5</option>
                                    </select>
<button type="button" class="btn btn-info" onclick="deployAll()">
<i class="fas fa-star"></i>&nbsp;{{ trans("cruds.report.explorer.deploy") }}
</button>
                                    &nbsp;
                                    &nbsp;

<div class="btn-group" role="group" aria-label="Direction">
    <input type="radio" class="btn-check" name="direction" id="direction-up" value="up" autocomplete="off">
    <label class="btn btn-outline-primary" for="direction-up">
        ↑ {{ trans("cruds.report.explorer.up") }}
    </label>

    <input type="radio" class="btn-check" name="direction" id="direction-down" value="down" autocomplete="off">
    <label class="btn btn-outline-primary" for="direction-down">
        ↓ {{ trans("cruds.report.explorer.down") }}
    </label>

    <input type="radio" class="btn-check" name="direction" id="direction-both" value="both" checked autocomplete="off">
    <label class="btn btn-outline-primary" for="direction-both">
        ↕ {{ trans("cruds.report.explorer.both") }}
    </label>
</div>
&nbsp;
<button id="toggleIP" class="btn btn-outline-secondary" type="button"
        data-bs-toggle="button" autocomplete="off">
    <i class="fa fa-eye"></i> Show IP
</button>

                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div id="mynetwork" style="height:700px;"></div>
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
        let _nodes = new Map();

        /**
         * Charger les données du graphe depuis l'API
         */
        async function loadGraphData() {
            const loadingIndicator = document.getElementById('loading-indicator');
            const loadingText = document.getElementById('loading-text');
            const controls = document.getElementById('explorer-controls');

            try {
                loadingText.textContent = 'Chargement des données du graphe...';

                const response = await fetch('{{ route("admin.reports.explore.data") }}', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }

                const data = await response.json();

                loadingText.textContent = 'Construction du graphe...';

                // Construire la Map des nœuds
                buildNodesMap(data.nodes, data.edges);

                loadingText.textContent = 'Initialisation de la visualisation...';

                // Peupler le sélecteur de nœuds
                populateNodeSelector(data.nodes);

                // Initialiser le réseau vis.js
                initializeNetwork();

                // Masquer le loading, afficher les contrôles
                loadingIndicator.style.display = 'none';
                controls.style.display = 'block';

                console.log(`✓ Graphe chargé: ${data.nodes.length} nœuds, ${data.edges.length} liens`);

                // Ajouter le/les nœuds passés en paramètre URL
                const urlParams = new URLSearchParams(window.location.search);
                const nodeParam = urlParams.get('node');

                if (nodeParam) {
                    // Split par virgule si plusieurs nœuds (ex: ?node=SITE_1,APP_2,SERVER_3)
                    const nodeIds = nodeParam.split(',');
                    nodeIds.forEach(nodeId => {
                        const trimmedId = nodeId.trim();
                        if (trimmedId && _nodes.has(trimmedId)) {
                            addNode(trimmedId);
                        }
                    });
                }

            } catch (error) {
                console.error('Erreur lors du chargement:', error);
                loadingIndicator.className = 'alert alert-danger';
                loadingText.textContent = `Erreur: ${error.message}`;
            }

        }

        /**
         * Construire la Map des nœuds avec leurs edges
         */
        function buildNodesMap(nodesData, edgesData) {
            // Index des edges par nœud pour recherche rapide
            const edgesByNode = new Map();

            edgesData.forEach(edge => {
                // Edge sortant
                if (!edgesByNode.has(edge.from)) {
                    edgesByNode.set(edge.from, []);
                }
                edgesByNode.get(edge.from).push({
                    attachedNodeId: edge.to,
                    name: edge.name,
                    edgeType: edge.type,
                    edgeDirection: 'TO',
                    bidirectional: edge.bidirectional,
                    color: edge.color
                });
                // Edge entrant
                if (!edgesByNode.has(edge.to)) {
                    edgesByNode.set(edge.to, []);
                }
                edgesByNode.get(edge.to).push({
                    attachedNodeId: edge.from,
                    name: edge.name,
                    edgeType: edge.type,
                    edgeDirection: 'FROM',
                    bidirectional: edge.bidirectional,
                    color: edge.color
                });
            });

            // Construire la Map finale des nœuds
            nodesData.forEach(node => {
                _nodes.set(node.id, {
                    id: node.id,
                    vue: String(node.vue),
                    label: node.label,
                    title: node.title || undefined,
                    image: node.image,
                    type: node.type,
                    order: node.order,
                    edges: edgesByNode.get(node.id) || []
                });
            });
        }

        /**
         * Peupler le sélecteur de nœuds
         */
        function populateNodeSelector(nodesData) {
            const selector = document.getElementById('node');
            selector.innerHTML = '<option></option>';

            nodesData.forEach(node => {
                const option = document.createElement('option');
                option.value = node.id;
                option.textContent = node.label;
                selector.appendChild(option);
            });
        }

        /**
         * Initialiser le réseau vis.js
         */
        function initializeNetwork() {
            // Create an array with nodes
            nodes = new vis.DataSet([]);

            // Create an array with edges
            edges = new vis.DataSet([]);

            // Create a network
            const container = document.getElementById('mynetwork');

            const data = {
                nodes: nodes,
                edges: edges
            };

            /*
            var options = {
                nodes: {
                    shape: 'image',
                    brokenImage: '/images/missing.png',
                    size: 30,
                    font: {
                        color: '#000000',
                        size: 12,
                        face: 'arial',
                        strokeWidth: 2,
                        strokeColor: '#ffffff'
                    }
                },
                edges: {
                    width: 0.15,
                    color: {inherit: 'from'},
                    smooth: {
                        type: 'continuous'
                    },
                    arrows: {
                        to: {
                            enabled: false
                        }
                    }
                },
                physics: {
                    enabled: true,
                    forceAtlas2Based: {
                        gravitationalConstant: -26,
                        centralGravity: 0.005,
                        springLength: 230,
                        springConstant: 0.18
                    },
                    maxVelocity: 146,
                    solver: 'forceAtlas2Based',
                    timestep: 0.35,
                    stabilization: {iterations: 150}
                }
            };
            */

            const options = {
                physics: {
                    enabled: true,
                    stabilization: {
                        enabled: true,
                        iterations: 1500
                    }
                },
                nodes: {
                    shape: 'image',
                    size: 25
                },
                edges: {
                    smooth: {
                        type: 'continuous'
                    }
                },
                interaction: {
                    navigationButtons: true,
                    keyboard: true
                }
            };

            network = new vis.Network(container, data, options);

            // Setup event handlers
            setupEventHandlers();
        }

        // Add a node base on the node.id
        function addNode(id) {
            const new_node = _nodes.get(id);
            if (new_node == null)
                return;
            // Check node already present
            if (!nodes.get(new_node.id)) {
                // Add new Node
                if (getShowIP() && (new_node.title!=null)) {
                    const modified_node = { ...new_node};
                    modified_node.label = new_node.label + "\n" + new_node.title;
                    network.body.data.nodes.add(modified_node);
                    }
                else
                    network.body.data.nodes.add(new_node);
            }
            // add edges
            const edgeList = new_node.edges;
            if (edgeList === undefined)
                return;

            // Loop on all edges
            for (const edge of edgeList) {
                var target_node = _nodes.get(edge.attachedNodeId);
                if (target_node !== undefined) {
                    if ((nodes.get(target_node.id) != null) &&
                    (exists(new_node.id, target_node.id, edge.name).length === 0)) {
                        if (edge.edgeType === 'FLUX') {
                            if (edge.edgeDirection === 'TO') {
                                if (edge.bidirectional)
                                    edges.add({
                                        label: edge.name,
                                        from: target_node.id,
                                        to: new_node.id,
                                        length: 200,
                                        arrows: {
                                            from: {enabled: true, type: 'arrow'},
                                            to: {enabled: true, type: 'arrow'}
                                        }
                                    });
                                else
                                    edges.add({
                                        label: edge.name,
                                        from: new_node.id,
                                        to: target_node.id,
                                        length: 200,
                                        arrows: {to: {enabled: true, type: 'arrow'}}
                                    });
                            } else if (edge.edgeDirection === 'FROM') {
                                if (edge.bidirectional)
                                    edges.add({
                                        label: edge.name,
                                        from: target_node.id,
                                        to: new_node.id,
                                        length: 200,
                                        arrows: {
                                            from: {enabled: true, type: 'arrow'},
                                            to: {enabled: true, type: 'arrow'}
                                        }
                                    });
                                else
                                    edges.add({
                                        label: edge.name,
                                        from: target_node.id,
                                        to: new_node.id,
                                        length: 200,
                                        arrows: {from: {enabled: true, type: 'arrow'}}
                                    });
                            }
                        } else if (edge.edgeType === 'CABLE') {
                            edges.add({
                            from: new_node.id, to: target_node.id,
                            color: edge.color ?? 'blue', width: 5,
                            length:200
                            });
                        } else if (edge.edgeType === 'LINK') {
                            edges.add({
                            from: new_node.id, to: target_node.id,
                            length:200
                            });
                        }
                    }
                }
            }
        }

        // Check that an edge already exists between 2 nodes
        function exists(from, to, label) {
            if (label === undefined)
                return edges.get({
                    filter: function (item) {
                        return (
                            ((item.from === from) && (item.to === to)) ||
                            ((item.to === from) && (item.from === to))
                            );
                    }
                });
            else
                return edges.get({
                    filter: function (item) {
                        return (
                            ((item.from === from) && (item.to === to) && (item.label === label)) ||
                            ((item.to === from) && (item.from === to) && (item.label === label))
                            );
                    }
                });
        }

        function setupEventHandlers() {
            network.on("afterDrawing", function (ctx) {
                if (needSavePNG) {
                    document.getElementById('canvasImg').href = ctx.canvas.toDataURL();
                    needSavePNG = false;
                }
            });

            var activeNode;
            network.on("oncontext", function (params) {
                console.log('Context menu on node:', params);
                params.event.preventDefault();
                activeNode = this.getNodeAt(params.pointer.DOM);
                console.log(activeNode);
                if (activeNode !== undefined) {
                    showContextMenu(params.event.pageX, params.event.pageY, activeNode);
                }
            });

            // Gestion du double-clic pour déployer
            network.on('doubleClick', async function(params) {
                if (params.nodes.length > 0) {
                    const nodeId = params.nodes[0];
                    await deployFromNode(nodeId, 1, new Set(), getFilter(), getDirection());
                }
            });

            document.addEventListener('click', function () {
                if (document.getElementById('explore_context') !== null) {
                    document.getElementById('explore_context').style.display = 'none';
                    document.getElementById('explore_context').innerHTML = '';
                }
            });
        }

        const contextMenu = document.getElementById("explore_context");

        function displayContextMenu(left, top) {
            contextMenu.style.display = "block";
            contextMenu.style.opacity = "1";
            contextMenu.style.top = top + "px";
            contextMenu.style.left = left + "px";
        }

        function hideContextMenu() {
            contextMenu.style.opacity = "0";
            contextMenu.style.display = "none";
        }

        function showContextMenu(left, top, nodeId) {
            const node = _nodes.get(nodeId);
            const type = node.type;
            const id = nodeId.split("_").pop();
            contextMenu.innerHTML = "<li><a href='/admin/" + type + "/" + id + "'>{{ trans("global.view") }}</a></li>" +
                "<li><a href='/admin/" + type + "/" + id + "/edit'>{{ trans("global.edit") }}</a></li>" +
                "<li id='hideNode' style='color: #167495; cursor: pointer;' ><span>{{ trans("global.hide") }}</span></li>"
            displayContextMenu(left, top);

            let hideNode = document.getElementById("hideNode");
            hideNode.addEventListener("click", function () {
                network.body.data.nodes.remove(node);
                hideContextMenu();
            });

        }

        function deployAll() {
            let activeNode = network.getSelectedNodes()[0];
            if (!activeNode) {
                alert("{{ trans("cruds.report.explorer.please_select") }}");
                return;
            }
            let depth = parseInt(document.getElementById('depth').value);
            let visitedNodes = new Set();
            let filter = getFilter();
            deployFromNode(activeNode, depth, visitedNodes, filter, getDirection());
        }

        function deployFromNode(nodeId, depth, visitedNodes, filter, direction = 3) {
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
                let targetNodeId = edge.attachedNodeId;
                if (nodes.get(targetNodeId) === null) {
                    let targetNode = _nodes.get(targetNodeId);
                    if (targetNode == null)
                        continue;
                    if (
                        ((filter.length === 0) || filter.includes(targetNode.vue))
                        ||
                        (filter.includes("8") && (edge.edgeType === 'CABLE'))
                        ||
                        (filter.includes("9") && (edge.edgeType === 'FLUX'))
                    ) {
                        // Check order Up
                        if ((direction === 1) && (node.order <= targetNode.order))
                            continue;
                        // Check order Down
                        if ((direction === 2) && (node.order >= targetNode.order))
                            continue;

                        // Add new node
                        if (getShowIP() && (targetNode.title!=null)) {
                            const modified_node = { ...targetNode};
                            modified_node.label = targetNode.label + "\n" + targetNode.title;
                            network.body.data.nodes.add(modified_node);
                            }
                        else
                            network.body.data.nodes.add(targetNode);

                        // Add edges between new node and other nodes
                        _nodes.get(targetNodeId).edges.forEach(neighborEdge => {
                            // Target node present
                            if (nodes.get(neighborEdge.attachedNodeId) !== null) {
                                addEdge(targetNodeId, neighborEdge.attachedNodeId);
                            }
                        })

                        setTimeout(function () {
                            deployFromNode(targetNodeId, depth - 1, visitedNodes, filter, direction);
                        }, 500);
                    }
                }
            }
        }

        const FONT_OPTIONS = {
            align: 'middle',
            background: 'white',
            strokeWidth: 2,
            strokeColor: 'white'
        };

        /**
         * Calcule la courbure en fonction du nombre de liens déjà présents entre deux nœuds.
         */
        function getSmooth(sourceNodeId, targetNodeId) {
            const count = edges.get().filter(e =>
                (e.from === sourceNodeId && e.to === targetNodeId) ||
                (e.from === targetNodeId && e.to === sourceNodeId)
            ).length;

            if (count === 0) return { enabled: false };

            return {
                enabled: true,
                type: count % 2 === 1 ? 'curvedCW' : 'curvedCCW',
                roundness: Math.ceil(count / 2) * 0.3
            };
        }

        function addEdge(sourceNodeId, targetNodeId) {
            const edgeList = _nodes.get(sourceNodeId).edges;

            for (const edge of edgeList) {
                if (edge.attachedNodeId !== targetNodeId) continue;
                if (exists(sourceNodeId, targetNodeId, edge.name).length > 0) continue;

                if (edge.edgeType === 'FLUX') {
                    const isFrom = edge.edgeDirection === 'FROM';
                    const [from, to] = isFrom
                        ? [targetNodeId, sourceNodeId]
                        : [sourceNodeId, targetNodeId];

                    edges.add({
                        label: edge.name,
                        from,
                        to,
                        length: 200,
                        smooth: getSmooth(from, to),
                        font: FONT_OPTIONS,
                        arrows: edge.bidirectional
                            ? { to: { enabled: true, type: 'arrow' }, from: { enabled: true, type: 'arrow' } }
                            : { to: { enabled: true, type: 'arrow' } }
                    });

                } else if (edge.edgeType === 'CABLE') {
                    edges.add({
                        from: sourceNodeId,
                        to: targetNodeId,
                        color: edge.color ?? 'blue',
                        width: 5,
                        length: 200,
                        smooth: getSmooth(sourceNodeId, targetNodeId)
                    });

                } else if (edge.edgeType === 'LINK') {
                    edges.add({
                        from: sourceNodeId,
                        to: targetNodeId,
                        length: 200,
                        smooth: getSmooth(sourceNodeId, targetNodeId)
                    });
                }
            }
        }
        function getFilter() {
            const filter = [];
            for (let option of document.getElementById('filters').options)
                if (option.selected)
                    filter.push(option.value);
            return filter
        }

        // 1 up, 2 down, 3 both
        function getDirection() {
            if (document.getElementById('direction-up').checked)
                return 1;
            else if (document.getElementById('direction-down').checked)
                return 2;
            else
                return 3;
        }

        function apply_filter() {
            cur_filter = $('#filters').val();
            if (cur_filter.length == 0) {
                for (let [node, value] of _nodes)
                    $("#node").append('<option value="' + value.id + '">' + value.label + '</option>');
            } else {
                let activated = 0, disabled = 0;
                $("#node").empty();
                for (let [node, value] of _nodes) {
                    if (cur_filter.includes(value.vue)) {
                        $("#node").append('<option value="' + value.id + '">' + value.label + '</option>');
                        activated++;
                    } else
                        disabled++;
                }
            }
            $('#node').val(null).trigger("change");
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Charger les données via AJAX
            loadGraphData();

            $('body').keydown(function (event) {
                if ((event.keyCode == 8) || (event.keyCode == 46)) {
                    network.deleteSelected()
                }
            });

            $('#filters').val(null).trigger('change');
            $('#node').val(null);

            $('#filters')
                .on('select2:select', function (e) {
                    apply_filter();
                });

            $('#filters')
                .on('select2:unselect', function (e) {
                    apply_filter();
                });

// Gestion du toggle Physique
let physicsEnabled = true;

const toggleBtn = document.getElementById('physicsToggle');
const icon = toggleBtn.querySelector('i');
const text = toggleBtn.querySelector('span');

toggleBtn.addEventListener('click', function() {
    physicsEnabled = !physicsEnabled;

    if (physicsEnabled) {
        // Mode actif (vert, play)
        toggleBtn.classList.remove('physics-inactive', 'btn-danger');
        toggleBtn.classList.add('physics-active', 'btn-success');
        icon.className = 'bi bi-play-fill';
        // Activer votre animation physique
        network.setOptions({physics: true});
    } else {
        // Mode inactif (rouge, stop)
        toggleBtn.classList.remove('physics-active', 'btn-success');
        toggleBtn.classList.add('physics-inactive', 'btn-danger');
        icon.className = 'bi bi-pause-fill';

        // Désactiver votre animation physique
        network.setOptions({physics: false});
    }
});

    // Gestion du toggle IP

    const toggleIP = document.getElementById('toggleIP');

    // Restaurer l'état au chargement
    if (localStorage.getItem('showIP') === 'true') {
        toggleIP.classList.add('active');
        refreshNodeLabels(true);
    }

    toggleIP.addEventListener('click', function () {
        const isActive = this.classList.contains('active');
        localStorage.setItem('showIP', isActive);
        refreshNodeLabels(isActive);
    });


});

    function getShowIP() {
        return  document.getElementById('toggleIP').classList.contains('active');
    }

    function refreshNodeLabels(showIP)
     {
         if (!nodes) return;
        nodes.forEach(function(visNode) {
            const srcNode = _nodes.get(visNode.id);
            if (srcNode) {
                const newLabel = (showIP && srcNode.title)
                    ? srcNode.label + "\n" + srcNode.title
                    : srcNode.label;
                if (visNode.label !== newLabel) {
                    nodes.update({ id: visNode.id, label: newLabel });
                }
            }
        });
    }
    </script>

    @parent

@endsection