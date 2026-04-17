import {Graphviz} from "@hpcc-js/wasm-graphviz";

Graphviz.load().then((graphviz) => {
    window.graphviz = graphviz;
    document.dispatchEvent(new Event('graphvizReady'));
});


