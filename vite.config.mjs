import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import fs from 'fs';

// Lire le fichier version.json
const version = fs.readFileSync('version.txt', 'utf-8').trim();

export default defineConfig({
    define: {
        'process.env.APP_VERSION': JSON.stringify(version),
    },
    plugins: [
        laravel({
            input: [
                // All common resources
                'resources/js/app.js',
                'resources/css/app.css',
                // Charts
                'resources/js/chart-home.js',
                'resources/js/chart-maturity.js',
                'resources/js/chart-relation.js',
                'resources/js/chart-patching.js',
                'resources/js/chart-relation.js',
                // CPE
                'resources/js/cpe.js',
                // Mapping TypeScript and styles
                'resources/css/mapping.css',
                // D3 Viz
                'resources/js/d3-viz.js',
                'resources/js/vis-network.js',
                // Maps
                'resources/js/map.show.ts',
                'resources/js/map.edit.ts',
                // BPMN
                'resources/js/bpmn.edit.ts'
            ],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '@': '/resources/js',
        },
    },
    build: {
        chunkSizeWarningLimit: 5000, // Augmente la limite à 5000 KB (5 MB)
    }
});
