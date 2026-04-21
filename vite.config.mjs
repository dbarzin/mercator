// vite.config.js
import {defineConfig} from 'vite';
import path from 'path';
import laravel from 'laravel-vite-plugin';
import {viteStaticCopy} from 'vite-plugin-static-copy';
import fs from 'fs';

const version = fs.readFileSync('version.txt', 'utf-8').trim();

export default defineConfig({
    define: {
        'process.env.APP_VERSION': JSON.stringify(version),
    },

    server: {
        host: 'localhost',
        port: 5173,
    },

    plugins: [
        laravel({
            input: [
                // Core
                'resources/js/app.js',
                'resources/css/app.css',
                // Charts
                'resources/js/chart-home.js',
                'resources/js/chart-maturity.js',
                'resources/js/chart-relation.js',
                'resources/js/chart-patching.js',
                // Mapping
                'resources/css/mapping.css',
                // D3 / Viz
                'resources/js/graphviz.js',
                'resources/js/vis-network.js',
                // Maps
                'resources/js/map.show.ts',
                'resources/js/map.edit.ts',
                // BPMN (ex-package autonome)
                'resources/ts/bpmn.ts',
                'resources/ts/bpmn-show.ts',
                // Parser
                'resources/js/sql-parser.js',
            ],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: 'resources/fonts/bpmn.ttf',
                    dest: 'fonts',
                },
            ],
        }),
    ],

    resolve: {
        alias: {
            '@': '/resources/js',
            '@ts': path.resolve(__dirname, 'resources/ts'), // imports BPMN
        },
    },

    build: {
        sourcemap: true,
        target: 'esnext',
        chunkSizeWarningLimit: 5000,
    },
});
