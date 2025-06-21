import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';
import tailwindcss from '@tailwindcss/vite';
import { defineConfig } from 'vite';
import collectModuleAssetsPaths from './vite-module-loader.js';

async function getConfig() {
    const paths = [
        'resources/js/app.js',
    ];
    
    const allPaths = await collectModuleAssetsPaths(paths, 'Modules');
 
    return defineConfig({
        plugins: [
            laravel({
                input: allPaths,
                ssr: 'resources/js/ssr.ts',
                refresh: true,
            }),
            tailwindcss(),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
        ],
        resolve: {
        alias: {
            '@': path.resolve(__dirname, './resources/js'),
        },
    },
    });
}


export default getConfig()
