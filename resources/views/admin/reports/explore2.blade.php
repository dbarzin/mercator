@extends('layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="card explorer-screen">
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
                                    </select>
                                    <span class="help-block">{{ trans("cruds.report.explorer.object_helper") }}</span>
                                </div>
                            </td>
                            <td style="text-align: left; vertical-align: middle;">
                                <a href="#" id="add_node_button"
                                   onclick="addNode(document.getElementById('node').value)">
                                    <i class="bi bi-plus-square-fill"></i>
                                </a>
                            </td>
                            <td style="text-align: right; vertical-align: right;">
                                &nbsp;
                                <a onclick="needSavePNG=true; network.redraw();document.getElementById('canvasImg').click();"
                                   href="#"><i class="fas fa-camera-retro"></i>
                                    Photo
                                </a>
                                <a id="canvasImg" download="filename"></a>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="3">
                                <a href="#" onclick="network.deleteSelected()" class="command">
                                    <i class="bi bi-dash-circle"></i>
                                    {{ trans("cruds.report.explorer.delete") }}
                                </a>
                                &nbsp;
                                <a href="#" onclick="nodes.clear(); edges.clear(); network.redraw();" class="command">
                                    <i class="bi bi-arrow-repeat"></i>
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
                                <span id="loading-indicator" style="display: none; margin-left: 10px;">
                                    <i class="fas fa-spinner fa-spin"></i> Chargement...
                                </span>
                            </td>
                        </tr>
                    </table>
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

        // Cache local des nodes disponibles
        let _nodesCache = new Map();

        // Cache des edges par node
        let _edgesCache = new Map();

        /**
         * Charge les nodes depuis l'API avec filtrage optionnel
         */
        async function loadNodes(filters = []) {
            console.log('Loading nodes with filters:', filters);

            showLoading(true);

            try {
                const params = new URLSearchParams();
                if (filters.length > 0) {
                    filters.forEach(filter => params.append('filter[]', filter));
                }

                const response = await fetch(`/admin/explorer/api/nodes?${params.toString()}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();

                if (result.success) {
                    // Mettre à jour le cache
                    _nodesCache.clear();
                    result.data.forEach(node => {
                        _nodesCache.set(node.id, node);
                    });

                    // Mettre à jour le select des nodes
                    updateNodeSelect();

                    console.log(`Loaded ${result.count} nodes`);
                }
            } catch (error) {
                console.error('Error loading nodes:', error);
                alert('Erreur lors du chargement des nodes');
            } finally {
                showLoading(false);
            }
        }

        /**
         * Charge les edges pour un node donné
         */
        async function loadEdgesForNode(nodeId, depth = 1) {
            console.log('Loading edges for node:', nodeId, 'depth:', depth);

            // Vérifier si déjà en cache
            const cacheKey = `${nodeId}_${depth}`;
            if (_edgesCache.has(cacheKey)) {
                return _edgesCache.get(cacheKey);
            }

            try {
                console.log('Fetching edges for node:', nodeId, 'depth:', depth);
                console.log(`/admin/explorer/api/node/${nodeId}/graph?depth=${depth}`);

                const response = await fetch(`/admin/explorer/api/node/${nodeId}/graph?depth=${depth}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const result = await response.json();


                if (result.success) {

                console.log(`Loaded ${result.edges} edges for node ${nodeId}`);

                    // Mettre en cache
                    _edgesCache.set(cacheKey, result.data);

                    // Ajouter les nouveaux nodes au cache
                    result.data.nodes.forEach(node => {
                        if (!_nodesCache.has(node.id)) {
                            _nodesCache.set(node.id, node);
                        }
                    });

                    return result.data;
                }
            } catch (error) {
                console.error('Error loading edges for node:', error);
                return { nodes: [], edges: [] };
            }
        }

        /**
         * Met à jour le select des nodes
         */
        function updateNodeSelect() {
            const $select = $('#node');
            $select.empty().append('<option></option>');

            // Trier par label
            const sortedNodes = Array.from(_nodesCache.values())
                .sort((a, b) => a.label.localeCompare(b.label));

            sortedNodes.forEach(node => {
                $select.append(`<option value="${node.id}">${node.label}</option>`);
            });

            $select.trigger('change');
        }

        /**
         * Ajoute un node au graphe et charge ses connexions
         */
        async function addNode(nodeId) {
            if (!nodeId) return;

            const nodeData = _nodesCache.get(nodeId);
            if (!nodeData) {
                console.warn('Node not found in cache:', nodeId);
                return;
            }

            // Vérifier si le node existe déjà
            if (nodes.get(nodeId)) {
                console.log('Node already in graph:', nodeId);
                return;
            }

            showLoading(true);

            try {
                // Ajouter le node au graphe
                nodes.add({
                    id: nodeData.id,
                    label: nodeData.label,
                    image: nodeData.image,
                    shape: 'image',
                    vue: nodeData.vue,
                    type: nodeData.type
                });

                // Charger le graphe autour de ce node (depth 1 par défaut)
                // const depth = parseInt(document.getElementById('depth').value) || 0;
                const depth = 0;
                const graphData = await loadEdgesForNode(nodeId, depth);

                console.log('Loaded graph for node:', nodeId, graphData);

                // Ajouter les nodes connectés
                graphData.nodes.forEach(connectedNode => {
                    if (!nodes.get(connectedNode.id)) {
                        nodes.add({
                            id: connectedNode.id,
                            label: connectedNode.label,
                            image: connectedNode.image,
                            shape: 'image',
                            vue: connectedNode.vue,
                            type: connectedNode.type
                        });
                    }
                });

                console.log('Added nodes:', graphData.nodes);
                console.log('Added edges:', graphData.edges);

                // Ajouter les edges
                graphData.edges.forEach(edge => {
                    // Vérifier que les deux nodes existent
                    if (nodes.get(edge.from) && nodes.get(edge.to)) {
                        addEdgeToGraph(edge);
                    }
                });

            } catch (error) {
                console.error('Error adding node:', error);
            } finally {
                showLoading(false);
            }
        }

        /**
         * Ajoute un edge au graphe avec le bon style
         */
        function addEdgeToGraph(edge) {
            // Vérifier si l'edge existe déjà
            if (edgeExists(edge.from, edge.to, edge.name)) {
                return;
            }

            const edgeOptions = {
                from: edge.from,
                to: edge.to
            };

            if (edge.type === 'FLUX') {
                edgeOptions.label = edge.name;
                edgeOptions.length = 200;

                if (edge.bidirectional) {
                    edgeOptions.arrows = {
                        from: { enabled: true, type: 'arrow' },
                        to: { enabled: true, type: 'arrow' }
                    };
                } else {
                    edgeOptions.arrows = {
                        to: { enabled: true, type: 'arrow' }
                    };
                }
            } else if (edge.type === 'CABLE') {
                edgeOptions.color = 'blue';
                edgeOptions.width = 5;
            } else if (edge.type === 'LINK') {
                // Style par défaut
            }

            edges.add(edgeOptions);
        }

        /**
         * Vérifie si un edge existe déjà
         */
        function edgeExists(from, to, label) {
            return edges.get({
                filter: edge =>
                    (edge.from === from && edge.to === to && edge.label === label) ||
                    (edge.from === to && edge.to === from && edge.label === label)
            }).length > 0;
        }

        /**
         * Déploie tous les nodes connectés depuis le node sélectionné
         */
        async function deployAll() {
            const selectedNodes = network.getSelectedNodes();
            if (selectedNodes.length === 0) {
                alert("{{ trans("cruds.report.explorer.please_select") }}");
                return;
            }

            const depth = parseInt(document.getElementById('depth').value) || 1;
            const filter = getFilter();

            showLoading(true);

            try {
                for (const nodeId of selectedNodes) {
                    await deployFromNode(nodeId, depth, new Set(), filter);
                }
            } finally {
                showLoading(false);
            }
        }

        /**
         * Déploie récursivement depuis un node
         */
        async function deployFromNode(nodeId, depth, visitedNodes, filter) {
            console.log('Deploying from node:', nodeId, 'depth:', depth);

            if (depth <= 0 || visitedNodes.has(nodeId)) {
                return;
            }

            visitedNodes.add(nodeId);

            // Charger le graphe
            const graphData = await loadEdgesForNode(nodeId, 1);

            // Filtrer les nodes selon le filtre actif
            const filteredNodes = graphData.nodes.filter(node => {
                if (filter.length === 0) return true;
                return filter.includes(node.vue.toString());
            });

            // Ajouter les nodes filtrés
            for (const node of filteredNodes) {
                if (!nodes.get(node.id)) {
                    nodes.add({
                        id: node.id,
                        label: node.label,
                        image: node.image,
                        shape: 'image',
                        vue: node.vue,
                        type: node.type
                    });

                    console.log('Added node:', graphData.edges);
                    // Ajouter les edges
                    graphData.edges
                        .filter(edge => edge.from === nodeId || edge.to === nodeId)
                        .forEach(edge => {
                            if (nodes.get(edge.from) && nodes.get(edge.to)) {
                                addEdgeToGraph(edge);
                            }
                        });

                    // Récursion avec délai pour éviter de bloquer l'UI
                    await new Promise(resolve => setTimeout(resolve, 100));
                    await deployFromNode(node.id, depth - 1, visitedNodes, filter);
                }
            }
        }

        /**
         * Récupère le filtre actif
         */
        function getFilter() {
            const filter = [];
            for (let option of document.getElementById('filters').options) {
                if (option.selected) {
                    filter.push(option.value);
                }
            }
            console.log('Filter:', filter);
            return filter;
        }

        /**
         * Applique le filtre aux nodes disponibles
         */
        async function applyFilter() {
            const filter = getFilter();
            await loadNodes(filter);
        }

        /**
         * Affiche/masque l'indicateur de chargement
         */
        function showLoading(show) {
            document.getElementById('loading-indicator').style.display = show ? 'inline' : 'none';
        }

        /**
         * Initialisation au chargement de la page
         */
        document.addEventListener('DOMContentLoaded', async () => {
            // Initialiser vis-network
            const container = document.getElementById('mynetwork');
            const data = {
                nodes: new vis.DataSet([]),
                edges: new vis.DataSet([])
            };

            const options = {
                physics: {
                    enabled: true,
                    stabilization: {
                        enabled: true,
                        iterations: 100
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
            nodes = data.nodes;
            edges = data.edges;

            // Event listeners
            $('#filters').on('select2:select select2:unselect', applyFilter);

            $('#physicsCheckbox').change(function() {
                network.setOptions({ physics: { enabled: this.checked } });
            });

            // Gestion du double-clic pour déployer
            network.on('doubleClick', async function(params) {
                console.log('Double-click on node:', params.nodes);
                if (params.nodes.length > 0) {
                    const nodeId = params.nodes[0];
                    const depth = parseInt(document.getElementById('depth').value) || 1;
                    await deployFromNode(nodeId, depth, new Set(), getFilter());
                }
            });

            // Context menu (si nécessaire)
            network.on('oncontext', function(params) {
                params.event.preventDefault();
                // Implémenter le menu contextuel
            });

            // Gestion des touches
            $('body').keydown(function(event) {
                if ((event.keyCode == 8) || (event.keyCode == 46)) {
                    network.deleteSelected();
                    event.preventDefault();
                }
            });

            // Screenshot
            network.on('afterDrawing', function(ctx) {
                if (needSavePNG) {
                    needSavePNG = false;
                    const canvas = document.querySelector('#mynetwork canvas');
                    document.getElementById('canvasImg').href = canvas.toDataURL();
                    document.getElementById('canvasImg').download = 'mercator-explorer-' + Date.now() + '.png';
                }
            });

            // Charger les nodes initiaux
            $('#filters').val([]).trigger('change');
            await loadNodes([]);
        });

    </script>

    @parent

@endsection