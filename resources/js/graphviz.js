// Graphviz (WASM)
import {Graphviz} from "@hpcc-js/wasm-graphviz";

window.graphvizReady = Graphviz.load()
    .then((graphviz) => {
        window.graphviz = graphviz;
        document.dispatchEvent(new Event('graphvizReady'));
        return graphviz;
    })
    .catch((err) => {
        console.error('Failed to load Graphviz WASM:', err);
        document.dispatchEvent(new CustomEvent('graphvizError', {detail: err}));
        throw err;
    });